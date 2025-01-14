ssh -p 8022 $USER@localhost 'mysql -h localhost -u $USER -p $PASSW $USER < init.sql'
