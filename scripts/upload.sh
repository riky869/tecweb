#! /bin/bash
rsync -e "ssh -p 8022 " -avrP ./src/ $USER@localhost:public_html/
rsync -e "ssh -p 8022 " -avrP cred_prod.php $USER@localhost:public_html/utils/cred.php
# rsync -e "ssh -p 8022 " -avrP scripts/init.sql $USER@localhost:init.sql
