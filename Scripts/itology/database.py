#! /usr/bin/env python

import MySQLdb

class Database:
	def __init__(self, dbName, usrName):
		self.dbName = dbName
		self.usrName = usrName

	def table_len(self, tblName):
		import table
		db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)
		
		table = table.Table(db, tblName)
		return len(table)

	def insert_industry_region(self, regionList):
		import regionTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)
		table = regionTable.RegionTable(db, "regions")
		table.insert_industry_region(regionList)
		db.commit()
		db.close()

	def insert_region(self, stateNo, stateAbrev, stateName, metroNo, metroName):
		import regionTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)
		table = regionTable.RegionTable(db, "regions")
		PK = table.insert(stateNo, stateAbrev, stateName, metroNo, metroName)
		db.commit()
		db.close()

		return PK


	def insert_state_occ(self, occStats):
		import occStatsTable

		for stats in occStats:
			regionPK = self.insert_region(stats[0], stats[1], stats[2], "00000", "STATE")
	
			db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)

			table = occStatsTable.OCCStatsTable(db, "occStats")
			table.insert(regionPK, stats[3], stats[4], stats[5], stats[6], stats[7])

			db.commit()
			db.close()


	def insert_metro_occ(self, occStats):
		import occStatsTable

		for stats in occStats:
			regionPK = self.insert_region("", stats[0], "", stats[1], stats[2])

			db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)

			table = occStatsTable.OCCStatsTable(db, "occStats")
			table.insert(regionPK, stats[3], stats[4], stats[5], stats[6], stats[7])

			db.commit()
			db.close()


	def insert_industriesNames(self):
		import industriesStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)

		table = industriesStatsTable.IndStatsTable(db, "indStats")
		table.insert_industries_names()

		db.commit()
		db.close()

		return


	def insert_state_industry(self, industryStats):
		import industriesStatsTable

		for stats in industryStats:
			#No info on state abbreviation, state name, metro number, or metro name
			regionPK = self.insert_region(stats[0], "", "", "00000", "STATE")
	
			db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)

			table = industriesStatsTable.IndStatsTable(db, "indStats")
			table.insert(regionPK, stats[1], stats[2], "INDUSTRY_NAME", stats[3],\
				stats[4], stats[5], stats[6], stats[7], stats[8])

			db.commit()
			db.close()


	def insert_metro_industry(self, industryStats):
		import industriesStatsTable
		import regionTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)
		for stats in industryStats:
			table = regionTable.RegionTable(db, "regions")
			stateNo = table.get_stateNo_from_metroNo(stats[0])
			assert -1 != stateNo
			regionPK = self.insert_region(stateNo, "", "", stats[0], "")
	
			table = industriesStatsTable.IndStatsTable(db, "indStats")
			table.insert(regionPK, stats[1], stats[2], "", stats[3],\
				stats[4], stats[5], stats[6], stats[7], stats[8])

		db.commit()
		db.close()

	def create_occ_states_salaries_csv(self):
		import occStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName,passwd="*****",host="127.0.0.1",port=3306)

		table = occStatsTable.OCCStatsTable(db, "occupationsStats")
		table.query_occ_states_salaries()

		db.close


	def create_occ_states_jobs_csv(self):
		import occStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName)

		table = occStatsTable.OCCStatsTable(db, "occupationsStats")
		table.query_occ_states_jobs()

		db.close


	def create_occ_metros_salaries_csv(self):
		import occStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName)

		table = occStatsTable.OCCStatsTable(db, "occupationsStats")
		table.query_occ_metros_salaries()

		db.close

	def create_occ_metros_jobs_csv(self):
		import occStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName)

		table = occStatsTable.OCCStatsTable(db, "occupationsStats")
		table.query_occ_metros_jobs()

		db.close


	def create_industry_jobs_csv(self):
		import industriesStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName)

		table = industriesStatsTable.IndStatsTable(db, "industriesStats")
		table.query_industry_jobs()

		db.close

	def create_industry_salaries_csv(self):
		import industriesStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName)

		table = industriesStatsTable.IndStatsTable(db, "industriesStats")
		table.query_industry_salaries()

		db.close

	def create_industry_orgs_csv(self):
		import industriesStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName)

		table = industriesStatsTable.IndStatsTable(db, "industriesStats")
		table.query_industry_orgs()

		db.close


	def create_industry_metros_csv(self):
		import industriesStatsTable

		db = MySQLdb.connect(db=self.dbName, user=self.usrName)

		table = industriesStatsTable.IndStatsTable(db, "industriesStats")
		table.query_industry_jobs()
		table.query_industry_salaries()
		table.query_industry_orgs()

		db.close



