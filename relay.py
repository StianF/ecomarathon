#!/usr/bin/python

import socket
import httplib
import urllib

C_PORT = 42424 		#Port used by telemetry unit
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
	
while 1:
	car_soc, addr = c_srv_soc.accept()
	while 1:
		data = car_soc.recv(1024)
		if not data:
			print("no data")
			break
		h1 = httplib.HTTPConnection(S_ADDR, S_PORT)
		h1.request("POST", PATH, urllib.urlencode({"data": data}), {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"})
		response = h1.getresponse()
		if response.status != 200:
			print("http error")
			break
		data = response.read()
		h1.close
	car_soc.close()
c_srv_soc.close()
