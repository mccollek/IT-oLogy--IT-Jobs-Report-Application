
import xlrd

def get_industries():
	industryList = []
	with open("naics", 'r') as fileHandle:
		for line in fileHandle: industryList.append(line.strip())
	return industryList


def parse_regions():
	regionList = []
	with open("data/area_titles.csv", 'r') as fileHandle:
		for line in fileHandle:
			code, name = line.split('","')
			name = name[:name.find("MSA")].strip()
			state = name.split(',')[1].split()[0][:2]
			regionList.append( [code[1:], name, state] )

	return regionList


def get_industries_names():
	industriesList = []

	with open("data/industry_titles.csv") as fileHandle:
		for line in fileHandle:
			values = line.split(',')
			try:
				naics = int(str(values[0]).replace('"', ''))
				name = ' '.join(str(item) for item in \
					str(values[1]).strip().replace('"', '').split()[2:])

				industriesList.append([naics, name])
			except ValueError, e:
				continue

	return industriesList

def get_data_list_formatENB(strFileName):
	dataList = []
	industryList = get_industries()

	with open(strFileName, 'r') as fileHandle:
		for line in fileHandle:
			if 0 == len(line.strip()): continue
			year = str(line[17:21])						#year
			head = [str(line[3:5])]						#State Code
			head.append(str(line[10]))					#Ownership Code

			naics = str(line[11:17]).strip()
			if naics in industryList:
				head.append(naics)						#NAICS Code
			else: continue

			head.append(str(line[21:23]))				#Aggregation Level

			for i in range(4):
				j = i*87
				quarter = [i + 1]						#Quarter
				quarter.append(int(line[24+j:32+j]))	#No of Establishments
	
				emp = 0
				for k in range(4):
					l = k*9
					if 0 != len(str.strip(line[32+j+l:41+j+l])):
						emp += int(line[32+j+l:41+j+l])
				quarter.append(emp)							#Avg No Employed
			
				quarter.append(13*int(line[102+j:110+j]))	#Avg Qtr Wage: 13*avg week wage
				quarter.append(year)
				dataList.append(head + quarter)

			head.append(5)							#Quarter 5 -> total
			head.append(int(line[372:380]))			#No of Establishments
			head.append(int(line[380:389]))			#No Employed
			head.append(int(line[440:449]))			#Total Annual Wages
			head.append(year)
			dataList.append(head)

	return dataList


def get_data_list_metro_formatENB(strFileName):
	dataList = []
	industryList = get_industries()

	with open(strFileName, 'r') as fileHandle:
		for line in fileHandle:
			if 0 == len(line.strip()): continue
			year = str(line[17:21])						#year
			head = [str(line[3:8])]						#Area Code
			head.append(str(line[10]))					#Ownership Code

			naics = str(line[11:17]).strip()
			if naics in industryList:
				head.append(naics)						#NAICS Code
			else: continue

			head.append(str(line[21:23]))				#Aggregation Level

			for i in range(4):
				j = i*87
				quarter = [i + 1]						#Quarter
				quarter.append(int(line[24+j:32+j]))	#No of Establishments
	
				emp = 0
				for k in range(4):
					l = k*9
					if 0 != len(str.strip(line[32+j+l:41+j+l])):
						emp += int(line[32+j+l:41+j+l])
				quarter.append(emp)							#Avg No Employed
			
				quarter.append(13*int(line[102+j:110+j]))	#Avg Qtr Wage: 13*avg week wage
				quarter.append(year)
				dataList.append(head + quarter)

			head.append(5)							#Quarter 5 -> total
			head.append(int(line[372:380]))			#No of Establishments
			head.append(int(line[380:389]))			#No Employed
			head.append(int(line[440:449]))			#Total Annual Wages
			head.append(year)
			dataList.append(head)

	return dataList

