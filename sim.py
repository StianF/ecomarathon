#!/usr/bin/python

import socket
import sys
import random
import time

S_PORT = 42424		#Port used to connect to the relayserver
S_ADDR = "81.167.78.33"	#Server address

s = None
for res in socket.getaddrinfo(S_ADDR, S_PORT, socket.AF_UNSPEC, socket.SOCK_STREAM, 0):
	af, socktype, proto, canonname, sa = res
	try:
		s = socket.socket(af, socktype, proto)
	except socket.error, msg:
		s = None
		continue
	try:
		s.connect(sa)
	except socket.error, msg:
		s.close()
		s = None
		continue
	break
if s == None:
	sys.exit(1)

gd = []
fd = open("out.txt")
for line in fd:
	gd.append(line)

while(1):
	for G in gd:
		time.sleep(1)
		data = ""

		data += "V,"
		for i in range(46):
			data += "%.2f" % ((random.random()%20)*0.08) + ","

		data += "S," + "%.2f" % ((random.random()%20)*0.45) + ","

		data += "T,"
		for i in range(12):
			data += "%.2f" % ((200+random.random()%300)*0.1) + ","

		data += "P,"
		for i in range(3):
			r = random.random() % 100
			if r == 0:
				data += "err,"
			else:
				data += "%.2f" % (r) + ","

		data += "O,"
		for i in range(5):
			data += "%.2f" % ((random.random()%24)) + ","

		data += "G," + G

		print(data)
		s.send(data)
