#!/usr/bin/python

import socket
import httplib
import urllib
import re

C_PORT = 45988 		#Port used by telemetry unit
C_ADDR = None
S_PORT = 80		#Port used to connect to the webserver
S_ADDR = "81.167.78.33"	#Server address
PATH   = "/ecomarathon/setdata.php"

for res in socket.getaddrinfo(C_ADDR, C_PORT, socket.AF_UNSPEC, socket.SOCK_STREAM, 0, socket.AI_PASSIVE):
	print(res)
	c_f, c_st, c_p, c_cn, c_sa = res
	c_srv_soc = socket.socket(c_f, c_st, c_p)
	c_srv_soc.bind(c_sa)
	c_srv_soc.listen(1)
	break
data = ""
while 1:
	try:
		car_soc, addr = c_srv_soc.accept()
	except KeyboardInterrupt:
		c_srv_soc.close()
		break
	except:
		c_srv_soc.close()
		continue
	car_soc.settimeout(20.0);
	try:
		while 1:
			data = ""
			temp = ""
			while not re.search("\n", data):
				temp = car_soc.recv(1024)
				data += temp
				if not temp:
					data = ""
					break
			if not data:
				print("no data")
				car_soc.close()
				break
			else:
				print(data)
				print("got some\n")
				h1 = httplib.HTTPConnection(S_ADDR, S_PORT)
				h1.request("POST", PATH, urllib.urlencode({"data": data}), {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"})
				response = h1.getresponse()
				print(response)
				if response.status != 200:
					print("http error")
					break
				data = response.read()
				data = ""
				h1.close
		car_soc.close()
	except:
		car_soc.close()
		continue
c_srv_soc.close()
