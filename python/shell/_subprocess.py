from subprocess import *

#run in the python3
shell = "for_test"
p = Popen([shell],stdin=PIPE,stdout = PIPE, shell = True,bufsize=0)

w = "hctf{S0_T3rr1b1e_Ps38}\n"
p.stdin.write(w.encode("GBK"))

#p.stdin.flush()

output = p.stdout.read()
#GBK in windows,utf-8 in linux
print(output.decode("GBK"))
