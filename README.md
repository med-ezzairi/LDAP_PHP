# LDAP_PHP
Web Application that gives you the ability to Administrate and Manage Active Directory via Secure Web (HTTPS) and Scure LDAPS

It's not an application package or an extension, It's a complete application (ready to use), with a few config on the web server and the DC (Domain Controller)

1) Config in the web Server:
  -PHP >=5.4.0
  -Apache Modules (open_ssl,open_LDAPmod_rewrite)
  -PHP Extension: (open_ssl,open_LDAP,pdf)
  -Create a self signed certificat to use with the server for HTTPS
  
2)Config in the DC:
  -Create a self signed certificat to use with AD server (for LDAPS)
  -Enable LDAPS
  -Enable web server to use the CA_certificat against the DC server
  
By those few config you're done to use your Web application

for more detail or further information
contact:med.ezzairi@gmail.com
