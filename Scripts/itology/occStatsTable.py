#! /usr/bin/env python

import table

import MySQLdb


class OCCStatsTable(table.Table):
	def insert_occupation(self, occ, occName):
		#query for existence
		res = self.dbc.execute("SELECT * FROM occNames WHERE occ = '%s'"%(occ))

		if 0 == res:
			#does not exist, insert
			self.dbc.execute("""INSERT INTO occNames (occ, occName)
				VALUES ('%s', '%s')"""%\
				(occ, MySQLdb.escape_string(occName)))

			#query for primary key
			res = self.dbc.execute("SELECT * FROM occNames WHERE occ = '%s'"%(occ))

		#return primary key
		return self.dbc.fetchone()[0]


	def insert(self, regionPK, occ, occName, jobs, salary, year):
		import occParser

		if occ not in occParser.get_occ_codes():
			return -1

		PK = self.insert_occupation(occ, occName)
		query = """SELECT * FROM occStats 
			WHERE fk_reg = '%s' AND fk_occ = '%s' AND jobs = '%s'
				AND salary = '%s' AND year = '%s'"""

		#query for existence
		res = self.dbc.execute(query%(regionPK, PK, jobs, salary, year))

		if 0 == res:
			#does not exist, insert
			res = self.dbc.execute("""INSERT INTO occStats 
				(fk_reg, fk_occ, jobs, salary, year)
				VALUES('%s', '%s', '%s', '%s', '%s')"""\
				%(regionPK, PK, jobs, salary, year))

			#query for primary key
			res = self.dbc.execute(query%(regionPK, PK, jobs, salary, year))

		#return primary key
		return self.dbc.fetchone()[0]

	def query_occ_states_jobs(self):
		self.query_occ_states(\
			"""SELECT occNames.occ, states.stateAbr, occStats.jobs,
				occStats.year
			FROM regions, states, occNames, occStats, metros
			WHERE occStats.fk_reg = regions.pk_reg
				AND occStats.fk_occ = occNames.pk_occ
				AND occNames.occ != '00-0000'
				AND regions.fk_metro = metros.pk_metro
				AND regions.fk_state = states.pk_state
				AND metros.metroNo = '0000000'
				AND ( """,\
			"State-Jobs.csv")


	def query_occ_states_salaries(self):
		self.query_occ_states(\
			"""SELECT occNames.occ, states.stateAbr, occStats.salary,
				occStats.year
			FROM regions, states, occNames, occStats, metros
			WHERE occStats.fk_occ = occnames.pk_occ
				AND occStats.fk_reg = regions.pk_reg
				AND occNames.occ != '00-0000'
				AND regions.fk_state = states.pk_state
				AND regions.fk_metro = metros.pk_metro
				AND metros.metroNo = '0000000'
				AND ( """,\
			"State-Salaries.csv")			

	def query_occ_states(self, query, fileName):
		import occParser

		occCodes = occParser.get_occ_codes()
		csv = []

		query += "occNames.occ = '{0}'\n".format(occCodes[0])
		for i in range(1, len(occCodes)):
			query += "\nOR occNames.occ = '{0}'".format(occCodes[i])

		query += """ )
			ORDER BY states.stateNo,
				occNames.occ,
				occStats.year desc;"""

		self.db.query(query)
		store = self.db.store_result()

		year = 2011
		row = store.fetch_row()[0]
		csvRow = [row[0], row[1]]
		while row:
			if year == int(row[3]):
				csvRow.append(int(row[2]))

				row = store.fetch_row()
				if row:	row = row[0]
				else:	continue
			else:
				csvRow.append('*')

			if(2000 == year):
				year = 2011
				csv.append(csvRow)
				csvRow = [row[0], row[1]]
			else:
				year -= 1 

		with open(fileName, 'wb') as fileHandle:
			fileHandle.write("OCC,STATE,2011,2010,2009,2008,2007,2006,2005,2004,2003,2002,2001,\
2000\n")
			strCSV = "{0},{1},{2},{3},{4},{5},{6},{7},{8},{9},{10},{11},{12},{13}\n"
			for line in csv:
				fileHandle.write(strCSV.format(line[0], line[1], line[2], line[3],\
					line[4], line[5], line[6], line[7], line[8], line[9], line[10],\
					line[11], line[12], line[13]))


	def query_occ_metros_jobs(self):
		self.query_occ_metros(
			"""SELECT occNames.occ, states.stateAbr, metros.metroName,\
				occStats.jobs, occStats.year
			FROM regions, states, occNames, occStats, metros
			WHERE occStats.FK_reg = regions.PK_reg
				AND occStats.FK_occ = occNames.PK_occ
				AND regions.FK_metro = metros.PK_metro
				AND regions.FK_state = states.PK_state
				AND metros.metroNo != '0000000'
				AND ( """,
			"Metros-Jobs.csv")

	def query_occ_metros_salaries(self):
		self.query_occ_metros(
			"""SELECT occNames.occ, states.stateAbr, metros.metroName,\
				occStats.salary, occStats.year
			FROM regions, states, occNames, occStats, metros
			WHERE occStats.FK_reg = regions.PK_reg
				AND occStats.FK_occ = occNames.PK_occ
				AND regions.FK_metro = metros.PK_metro
				AND regions.FK_state = states.PK_state
				AND metros.metroNo != '0000000'
				AND ( """,
			"Metros-Salaries.csv")

	def query_occ_metros(self, query, fileName):
		import occParser

		occCodes = occParser.get_occ_codes()
		csv = []

		query += "occNames.occ = '{0}'\n".format(occCodes[0])
		for i in range(1, len(occCodes)):
			query += "\nOR occNames.occ = '{0}'".format(occCodes[i])

		query += """ )
			ORDER BY states.stateNo,
				metros.metroNo,
				occNames.occ,
				occStats.year desc;"""

		self.db.query(query)
		store = self.db.store_result()

		year = 2011
		row = store.fetch_row()[0]
		csvRow = [row[0], row[1], row[2]]
		while row:
			if year == int(row[4]):
				csvRow.append(int(row[3]))

				row = store.fetch_row()
				if row:	row = row[0]
				else:	continue
			else:
				csvRow.append('*')

			if(2000 == year):
				year = 2011
				csv.append(csvRow)
				csvRow = [row[0], row[1], row[2]]
			else:
				year -= 1 

		with open(fileName, 'wb') as fileHandle:
			fileHandle.write("OCC;STATE;METROS;2011;2010;2009;2008;2007;2006;2005;2004;2003;\
2002;2001;2000\n")
			strCSV = "{0};{1};{2};{3};{4};{5};{6};{7};{8};{9};{10};{11};{12};{13};{14}\n" 
			for line in csv:
				fileHandle.write(strCSV.format(line[0], line[1], line[2], line[3],\
					line[4], line[5], line[6], line[7], line[8], line[9], line[10],\
					line[11], line[12], line[13], line[14]))



