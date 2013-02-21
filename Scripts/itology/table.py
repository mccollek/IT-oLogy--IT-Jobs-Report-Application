#! /usr/bin/env python

import MySQLdb

class Table: 
	def __init__(self, db, name): 
		self.db = db 
		self.name = name 
		self.dbc = self.db.cursor() 
	
	def __len__(self): 
		self.dbc.execute("select count(*) from %s" % (self.name)) 
		l = int(self.dbc.fetchone()[0]) 
		return l

	def max(self, column):
		res = self.dbc.execute("SELECT MAX({0}) FROM {1}".format(column, self.name))
		if 0 != res:
			return int(self.dbc.fetchone()[0])
		else:
			return -1

	def min(self, column):
		res = self.dbc.execute("SELECT MIN({0}) FROM {1}".format(column, self.name))
		if 0 != res:
			return int(self.dbc.fetchone()[0])
		else:
			return -1

