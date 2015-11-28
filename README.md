# LDAP_PHP
Web Application that gives you the ability to Administrate and Manage Active Directory via Secure Web (HTTPS) and Scure LDAPS

It's not an application package or an extension, It's a complete application (ready to use), with a few config on the web server and the DC (Domain Controller)


1) Config in the web Server:

  -PHP >=5.4.0 <br>
  -Apache Modules (open_ssl,open_LDAP,mod_rewrite)<br>
  -PHP Extension: (open_ssl,open_LDAP,pdf)<br>
  -Create a self signed certificat to use with the server for HTTPS<br>
  
2)Config in the DC:<br>
  -Create a self signed certificat to use with AD server (for LDAPS)<br>
  -Enable LDAPS<br>
  -Enable web server to use the CA_certificat against the DC server<br>
  
By those few config you're done to use your Web application<br>

for more detail or further information<br>
contact:med.ezzairi@gmail.com
