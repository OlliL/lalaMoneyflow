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

#mysqldump -u root --set-variable=triggers=FALSE --set-variable=quote-names=FALSE --default-character-set=latin1 --tables --add-drop-table --no-data moneyflow \
mysqldump -u root --skip-quote-names --skip-triggers --default-character-set=latin1 --tables --add-drop-table --no-data moneyflow \
	users \
	settings \
	capitalsources \
	contractpartners \
	moneyflows \
	monthlysettlements \
	postingaccounts \
	predefmoneyflows \
	languages \
	text \
	imp_data \
	imp_mapping_source \
	imp_mapping_partner \
	cmp_data_formats \
	groups \
	user_groups \
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

#mysqldump -u root --set-variable=quote-names=FALSE --set-variable=extended-insert=FALSE --default-character-set=latin1 --tables moneyflow \
mysqldump -u root --skip-quote-names --skip-extended-insert --skip-triggers --default-character-set=latin1 --tables moneyflow \
	languages \
	text \
	cmp_data_formats \
		|grep INSERT >> ${PROGPATH}/mysqldump.sql

cat << EOF >> ${PROGPATH}/mysqldump.sql
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('','',0,0,0);
UPDATE users SET userid=0 WHERE name='';
INSERT INTO settings VALUES (0,'displayed_language','1'),(0,'max_rows','40'),(0,'date_format','YYYY-MM-DD'),(0,'num_free_moneyflows','1');
INSERT INTO settings (SELECT (SELECT userid FROM users WHERE name='admin'),name,value FROM settings WHERE mur_userid=0);
EOF

sed -i.bak "s/\\\'/''/g" ${PROGPATH}/mysqldump.sql && rm -f ${PROGPATH}/mysqldump.sql.bak
