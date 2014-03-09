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

mysqldump -u root --skip-quote-names --skip-triggers --default-character-set=latin1 --tables --add-drop-table --no-data moneyflow \
	access \
	access_relation \
	access_flattened \
	settings \
	capitalsources \
	contractpartners \
	moneyflows \
	monthlysettlements \
	postingaccounts \
	predefmoneyflows \
	imp_data \
	imp_mapping_source \
	imp_mapping_partner \
	cmp_data_formats \
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

mysqldump -u root --skip-quote-names --skip-extended-insert --skip-triggers --default-character-set=latin1 --tables moneyflow \
	cmp_data_formats \
		|grep INSERT >> ${PROGPATH}/mysqldump.sql

cat << EOF >> ${PROGPATH}/mysqldump.sql
INSERT INTO access (name,password,att_user,att_change_password,perm_login,perm_admin) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1,1);
INSERT INTO access (name,password,att_user,att_change_password,perm_login,perm_admin) VALUES ('admingroup',NULL,0,0,0,0);
INSERT INTO access (name,password,att_user,att_change_password,perm_login,perm_admin) VALUES ('root','NULL',0,0,0,0);
UPDATE access SET id=0 WHERE name='root';
UPDATE access SET id=1 WHERE name='admingroup';
UPDATE access SET id=2 WHERE name='admin';
INSERT INTO access_relation (id,ref_id,validfrom,validtil) VALUES (1,0,'0001-01-01','2999-12-31');
INSERT INTO access_relation (id,ref_id,validfrom,validtil) VALUES (2,1,'0001-01-01','2999-12-31');
INSERT INTO access_flattened (id,validfrom,validtil,id_level_1,id_level_2,id_level_3) VALUES (2,'0001-01-01','2999-12-31',2,1,0);
INSERT INTO settings VALUES (0,'displayed_language','1'),(0,'max_rows','40'),(0,'date_format','YYYY-MM-DD'),(0,'num_free_moneyflows','1');
INSERT INTO settings (SELECT 2,name,value FROM settings WHERE mac_id=0);
EOF

sed -i.bak "s/\\\'/''/g" ${PROGPATH}/mysqldump.sql && rm -f ${PROGPATH}/mysqldump.sql.bak
