#!/bin/sh -e

PROGNAME=$(basename $0)
PROGPATH=$0
if [ $PROGNAME = $PROGPATH -o $PROGPATH = '.' ] ; then
        PROGPATH=$(pwd)
else
        [ -z "$(echo "$PROGPATH" | sed 's![^/]!!g')" ] && \
        PROGPATH=$(type "$PROGPATH" | sed 's/^.* //g')
        PROGPATH=$(dirname $PROGPATH)
fi

mysqldump -u root --set-variable=triggers=FALSE --set-variable=quote-names=FALSE --default-character-set=latin1 --tables --add-drop-table --no-data moneyflow \
	users \
	settings \
	capitalsources \
	contractpartners \
	currencies \
	currencyrates \
	moneyflows \
	monthlysettlements \
	predefmoneyflows \
	languages \
	templates \
	text \
	enumvalues \
		> ${PROGPATH}/mysqldump.sql

mysqldump -u root --set-variable=quote-names=FALSE --set-variable=extended-insert=FALSE --default-character-set=latin1 --tables moneyflow \
	currencies \
	currencyrates \
	languages \
	text \
	templates \
	enumvalues \
		|grep INSERT >> ${PROGPATH}/mysqldump.sql

cat << EOF >> ${PROGPATH}/mysqldump.sql
INSERT INTO settings VALUES (0,'displayed_currency','1'),(0,'displayed_language','1');
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('','',0,0,0);
UPDATE users SET id=0 WHERE username='';
EOF

sed -i.bak "s/\\\'/''/g" ${PROGPATH}/mysqldump.sql && rm -f ${PROGPATH}/mysqldump.sql.bak
