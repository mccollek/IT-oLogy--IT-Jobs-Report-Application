#! /usr/bin/env python

import table

import MySQLdb


class IndStatsTable(table.Table):

	def insert_industries_names(self):
		import industryParser

		industryNames = industryParser.get_industries_names()

		query = "UPDATE industries SET industryName = '{1}' WHERE naics = '{0}'"
		for naics, name in industryNames:
			self.dbc.execute(query.format(naics, MySQLdb.escape_string(name)))

		return


	def insert_industry(self, naics, indName):
		#query for existence
		query = "SELECT * FROM industries WHERE naics = '{0}'".format(naics)

		res = self.dbc.execute(query)
		if 0 == res:
			#does not exist, insert
			self.dbc.execute("""INSERT INTO industries (naics, industryName)
				VALUES ('%s', '%s')"""%\
				(naics, MySQLdb.escape_string(indName)))

			#query for primary key
			res = self.dbc.execute(query)

		#return primary key
		return self.dbc.fetchone()[0]


	def insert(self, regionPK, owner, naics, indName, agg, quarter, orgs, jobs, salary,\
	year):
		import industryParser

		if naics not in industryParser.get_industries():
			return -1

		#insert industry if new, get industry primary key if not
		naicsPK = self.insert_industry(naics, indName)

		query = """SELECT * FROM industriesStats 
			WHERE FK_region = '{0}' AND FK_naics = '{1}' AND aggregation = '{2}'
				AND jobs = '{3}' AND orgs = '{4}' AND salary = {5}
				AND year = '{6}' AND quarter = '{7}' AND ownership = '{8}'"""\
			.format(regionPK, naicsPK, agg, jobs, orgs, salary, year, quarter, owner)

		#query for existence
		res = self.dbc.execute(query)
		if 0 == res:
			#does not exist, insert
			res = self.dbc.execute("""INSERT INTO industriesStats 
				(FK_region, FK_naics, aggregation, jobs, orgs, salary, year, quarter,
					ownership)
				VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')"""\
				%(regionPK, naicsPK, agg, jobs, orgs, salary, year, quarter, owner))

			#query for primary key
			res = self.dbc.execute(query)

		#return primary key
		return self.dbc.fetchone()[0]



	def query_industry_jobs(self):
		#self.query_state_industries("industriesStats.jobs", "Industries-Jobs.csv")
		self.query_metro_industries("industriesStats.jobs", "Industries-MetroJobs.csv")

	def query_industry_salaries(self):
		#self.query_state_industries("industriesStats.Salary", "Industries-Salaries.csv")
		self.query_metro_industries("industriesStats.Salary",\
			"Industries-MetroSalaries.csv")

	def query_industry_orgs(self):
		#self.query_state_industries("industriesStats.orgs", "Industries-Orgs.csv")
		self.query_metro_industries("industriesStats.orgs", "Industries-MetroOrgs.csv")


	def query_state_industries(self, column, filename):
		queryhead = """select industries.naics, states.stateabrev,
			sum({0}), industriesstats.year
		from industriesstats, industries, regions, states
		where industriesstats.fk_naics = industries.pk_industry
			and industriesstats.fk_region = regions.pk_region
			and regions.fk_state = states.pk_state and industriesstats.quarter = '5'
			and (""".format(column)

		querytail += """ )
			group by states.stateno, industries.naics, industriesstats.year
			order by states.stateno, industries.naics, industriesstats.year desc;"""

		self.query_industries(queryhead, querytail, filename)


	def query_metro_industries(self, column, filename):
		queryhead = """SELECT industries.naics, metros.metroName, states.stateAbrev,
			SUM({0}), industriesStats.year
		FROM industriesStats, industries, regions, metros, states
		WHERE industriesStats.FK_naics = industries.PK_industry
			AND industriesStats.FK_region = regions.PK_region
			AND regions.FK_metro = metros.PK_metro AND regions.FK_state = states.PK_state
			AND industriesStats.quarter = '5'
			AND industriesStats.ownership != '0'
			AND metros.metroName != 'STATE'
			AND (""".format(column)

		querytail = """ )
			GROUP BY states.stateNo, metros.metroNo, industries.naics, industriesStats.year
			ORDER BY states.stateNo, metros.metroNo, industries.naics, industriesStats.year
				desc;"""

		self.query_industries(queryhead, querytail, filename)


	def query_industries(self, queryHead, queryTail, fileName):
		import industryParser

		naics = industryParser.get_industries()
		csv = []

		query = queryHead
		query += "industries.naics = '{0}'\n".format(naics[0])
		for i in range(1, len(naics)):
			query += "\nOR industries.naics = '{0}'".format(naics[i])
		query += queryTail

		res = self.db.query(query)
		store = self.db.store_result()

		maxYear = self.max("year")
		minYear = self.min("year")

		row = store.fetch_row()[0]
		csvRow = [row[0], row[1], row[2]]
		while row:
			for year in range(maxYear, minYear-1, -1):
				if row and int(row[4]) == year:
					csvRow.append(int(row[3]))

					row = store.fetch_row()
					if		row: row = row[0]
					else:	continue
				else:
					csvRow.append('*')

			csv.append(csvRow)

			if row: csvRow = [row[0], row[1], row[2]]

		with open(fileName, 'wb') as fileHandle:
			fileHandle.write("NAICs;METRO;STATE;\
2010;2009;2008;2007;2006;2005;2004;2003;2002;2001;2000\n")
			strCSV = "{0};{1};{2};{3};{4};{5};{6};{7};{8};{9};{10};{11};{12};{13}\n"
			for line in csv:
				fileHandle.write(strCSV.format(line[0], line[1], line[2], line[3],\
					line[4], line[5], line[6], line[7], line[8], line[9], line[10],\
					line[11], line[12], line[13]))

