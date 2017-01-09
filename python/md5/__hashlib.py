import hashlib

tmp1 = "easy" #md5(easy,32) = 48bb6e862e54f2a795ffc4e541caed4d
tmp2 = "hard" #md5(hard,32) = d64a84456adc959f56de6af685d0dadd
UseFor_md5 = hashlib.md5()
UseFor_md5.update(tmp1.encode("utf-8"))
print("md5(easy,32) = ", UseFor_md5.hexdigest())
#every time need to rebuild the hashlib.md5()
UseFor_md5 = hashlib.md5()
UseFor_md5.update(tmp2.encode("utf-8"))
print("md5(hard,32) = ", UseFor_md5.hexdigest())