#!/usr/bin/python

import socket
import sys

#S_PORT = 42424		#Port used to connect to the relayserver
#S_ADDR = "81.167.78.33"	#Server address
#
#s = None
#for res in socket.getaddrinfo(C_ADDR, C_PORT, socket.AF_UNSPEC, socket.SOCK_STREAM, 0):
#	af, socktype, proto, canonname, sa = res
#	try:
#		s = socket.socket(af, socktype, proto)
#	except socket.error, msg:
#		s = None
#		continue
#	try:
#		s.connect(sa)
#	except socket.error, msg:
#		s.close()
#		s = None
#		continue
#	break
#if s == None:
	#sys.exit(1)

gd = []
fd = open("out.txt")
for line in fd:
	gd.append(line)

while(1):
	for G in gd:
		data = ""

		data += "V,"
		for i in range(45):
			data += str((rand()%20)*0.08) + ","

		data += "S," + str((rand()%20)*0.45)

		data += "T,"
		for i in range(12):
			data += str((200+rand()%300)*0.1) + ","

		data += "P,"
		for i in range(3):
			r = rand() % 100
			if r == 0:
				data += "err,"
			else:
				data += str(r) + ","

		data += "O,"
		for i in range(5):
			data += str((rand()%24)) + ","

		data += "G," + G

		data += "\n"
		#s.send(data)
