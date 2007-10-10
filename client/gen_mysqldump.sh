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
	templatevalues \
	text \
	domains \
	domainvalues \
	imp_data \
	imp_mapping_source \
	imp_mapping_partner \
		| awk '
	{
		if( $1 == ")" ) {
			printf("%s",$1)
			for( i=2 ; i <= NF ; i++ ) {
				if( $i !~ /AUTO_INCREMENT=[0-9]*/ )
					printf (" %s",$i)
			}
			printf("\n")
		} else {
			print
		}
	}' > ${PROGPATH}/mysqldump.sql

mysqldump -u root --set-variable=quote-names=FALSE --set-variable=extended-insert=FALSE --default-character-set=latin1 --tables moneyflow \
	currencies \
	currencyrates \
	languages \
	text \
	templates \
	templatevalues \
	domains \
	domainvalues \
		|grep INSERT >> ${PROGPATH}/mysqldump.sql

cat << EOF >> ${PROGPATH}/mysqldump.sql
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('','',0,0,0);
UPDATE users SET id=0 WHERE username='';
INSERT INTO settings VALUES (0,'displayed_currency','1'),(0,'displayed_language','1'),(0,'max_rows','40'),(0,'date_format','YYYY-MM-DD');
INSERT INTO settings (SELECT (SELECT userid FROM users WHERE name='admin'),name,value FROM settings WHERE mur_userid=0);
EOF

sed -i.bak "s/\\\'/''/g" ${PROGPATH}/mysqldump.sql && rm -f ${PROGPATH}/mysqldump.sql.bak
