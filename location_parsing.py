# with open("C:/Users/weicheng/Desktop/Jun 14, 2022 at 21.txt") as f:
#     text = f.readlines()

import mysql.connector
# pip install mysql-connector-python
try:
    cnx = mysql.connector.connect(user='weicheng', password='awc020826',
                              host='150.230.127.102',
                              database='diary', auth_plugin='mysql_native_password')
except:
    exit(0)

cursor = cnx.cursor()

query = ("SELECT * FROM location")

cursor.execute(query)

for thing in cursor:
    # print(thing[1])
    raw = things[1]

    # break

cursor.close()
cnx.close()
# print(text)