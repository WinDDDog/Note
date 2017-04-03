import sys

print("you enter the ", len(sys.argv), "arguments")
print("they are : ", str(sys.argv) )
for tmp in sys.argv:
    print(tmp)
