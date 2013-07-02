import xlrd


def get_occ_codes():
	strFileName = "occ"
	occ_codes = []

	try:
		with open(strFileName, 'rbU') as fileHandle:
			for line in fileHandle.readlines():
				occ_codes.append(str.strip(line))
		return occ_codes
	except IOError, ioE:
		print "Error reading file occ_codes"
		return []
	except EOFError: #file is empty
		print "File occ_codes is empty"
		return []


def parse_file(fileName, year):
	sheetNo = 0
	initRow2000 = 43
	initRow2001_10 = 1
	col2010 = [0, 1, 2, 3, 4, 6, 11, 10]
	col2009 = [0, 1, 2, 3, 4, 6, 10, 9]
	col2000_08 = [0, 1, 2, 3, 4, 6, 9, 8]

	if "2000" == year:
		return parse_file_format(fileName, year, sheetNo, initRow2000, col2000_08)
	elif 2000 < int(year) and int(year) < 2009:
		return parse_file_format(fileName, year, sheetNo, initRow2001_10, col2000_08)
	elif "2009" == year:
		return parse_file_format(fileName, year, sheetNo, initRow2001_10, col2009)
	elif "2010" == year or "2011" == year:
		return parse_file_format(fileName, year, sheetNo, initRow2001_10, col2010)
	else:
		return "No format for year {0}".format(year)


def parse_file_format(strFileName, year, sheetNo, initRow, col):
	dataList = []
	occList = get_occ_codes()

	wb = xlrd.open_workbook(strFileName)
	sh = wb.sheet_by_index(sheetNo)

	for rowNo in range(initRow, sh.nrows):
		row = sh.row_values(rowNo)
		if row[col[3]] not in occList: continue

		rowList = [str(row[col[0]]).strip()]
		rowList.append(str(row[col[1]]).strip())
		rowList.append(str(row[col[2]].encode('utf-8')).strip())
		rowList.append(str(row[col[3]]).strip())
		rowList.append(str(row[col[4]]).strip())

		if row[col[5]] not in ["*", "**", "#"]:
			rowList.append(int(row[col[5]]))
		else:
			rowList.append(int(-1))

		if row[col[6]] not in ["*", "**", "#"]:
			rowList.append(float(row[col[6]]))
		else:
			if row[col[7]] not in ["*", "**", "#"]:
				rowList.append(40*52*float(row[col[7]]))
			else:
				rowList.append(int(-1))
		
		rowList.append(year)
		dataList.append(rowList)

	return dataList



