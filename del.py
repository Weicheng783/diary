import time
import os, sys

time.sleep(3*60)
os.remove("/home/ubuntu/www/public/report/"+sys.argv[1]+".pdf")

