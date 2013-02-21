#! /usr/bin/env python

import table

import MySQLdb


class RegionTable(table.Table):

	def insert_state(self, stateNo, stateAbrev, stateName):
		query = "SELECT * FROM states WHERE stateNo = '%s'"
		#query for existence
		if 0 == self.dbc.execute(query%(stateNo)):
			assert 0 == len(stateNo) and 0 == len(stateName), "wrong format for metro"
			#does not exist, insert
			self.dbc.execute("""INSERT INTO states (stateNo, stateAbrev, stateName)
				VALUES ('%s', '%s', '%s')"""%\
				(stateNo, stateAbrev, MySQLdb.escape_string(stateName)))

			#query for primary key
			assert 1 == self.dbc.execute(query%(stateAbrev))

		#return primary key
		return int(self.dbc.fetchone()[0])


	def get_state(self, stateAbrev):
		query = "SELECT * FROM states WHERE stateAbrev = '%s'"
		res = self.dbc.execute(query%region[2])
		if 0 == res:
			return -1
		else:
			return int(self.dbc.fetchone()[0])


	def insert_industry_region(self, regionList):
		for region in regionList:
			statePK = self.get_state(region[2])
			metroPK = self.insert_metro(region[0], region[1])
			self.dbc.execute("""INSERT INTO regions (FK_state, FK_metro)
				VALUES ('%s', '%s')"""%(statePK, metroPK))


	def get_stateNo_from_metroNo(self, metroNo):
		if 1 == self.dbc.execute(\
			"""SELECT states.StateNo
			FROM regions, metros, states
			WHERE regions.FK_state = states.PK_state
				AND regions.FK_metro = metros.PK_metro
				AND metros.metroNo = '{0}'""".format(metroNo)):
			return self.dbc.fetchone()[0]
		else:
			return -1
			

	def insert_metro(self, metroNo, metroName):
		#query for existence
		res = self.dbc.execute("SELECT * FROM metros WHERE metroNo = '%s'"%(metroNo))

		if 0 == res:
			#does not exist, insert
			self.dbc.execute("""INSERT INTO metros (metroNo, metroName)
				VALUES ('%s', '%s')"""%\
				(metroNo, MySQLdb.escape_string(metroName)))

			#query for primary key
			res = self.dbc.execute("SELECT * FROM metros WHERE metroNo = '%s'"%(metroNo))

		#return primary key
		return int(self.dbc.fetchone()[0])


	def insert(self, stateNo, stateAbrev, stateName, metroNo, metroName):
		if -1 == stateNo:
			statePK = self.get_state(stateAbrev)
		else:
			statePK = self.insert_state(stateNo, stateAbrev, stateName)
			
		metroPK = self.insert_metro(metroNo, metroName)

		#query for existence / primary key
		query = "SELECT * FROM regions WHERE FK_state = '{0}' AND FK_metro = '{1}'"\
			.format(statePK, metroPK)

		res = self.dbc.execute(query)
		if 0 == res:
		#does not exist, insert
			self.dbc.execute("""INSERT INTO regions (FK_state, FK_metro)
				VALUES ('%s', '%s')"""%(statePK, metroPK))

			#query for primary key
			res = self.dbc.execute(query)

		#return primary key
		return int(self.dbc.fetchone()[0])






















	def update_metro(self):
		#query for existence
		res = self.dbc.execute("SELECT metroNo FROM metros")
		metroNoList = []
		while (1):
			row = self.dbc.fetchone()
			if row == None:
				break
			metroNoList.append(row[0])

		for metroNo in metroNoList:
			if 5 == len(metroNo):
				self.dbc.execute("UPDATE metros SET metroNo ='%s' WHERE metroNo = '%s'"\
					% ("00"+metroNo, metroNo))
				print "%s -> %s" % (metroNo, "00"+metroNo)



	def update_metro(self):
		#query for existence
		res = self.dbc.execute("SELECT metroNo FROM metros")
		metroNoList = []
		while (1):
			row = self.dbc.fetchone()
			if row == None:
				break
			metroNoList.append(row[0])

		for metroNo in metroNoList:
			if 5 == len(metroNo):
				self.dbc.execute("UPDATE metros SET metroNo ='%s' WHERE metroNo = '%s'"\
					% ("00"+metroNo, metroNo))
				print "%s -> %s" % (metroNo, "00"+metroNo)

