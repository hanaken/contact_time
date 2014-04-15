dbHost='localhost';
dbUser='root';
dbPass='hk08336';
dbName='contacttime';
fileName='backup/'`date '+%Y%m'`'.txt';
mysqldump --default-character-set=binary $dbName --host=$dbHost --user=$dbUser --password=$dbPass > $fileName
