# classwall-Cookies-Sessions-and-PostgreSQL-Activity

Short answers to these questions:

1. What is the difference between a cookie and a session?
   -A cookie is a small piece of data stored in the browser that can last for days or longer, until it expires. A session is data kept on the server for a visitor, and it usually ends when the browser closes.

2. Where is each one stored (browser or server)?
   -A cookie is stores in the browser, on the visitor’s own computer. While a session is On the server.

3. Why do we use prepared statements?
   -Prepared statements add extra protection, so a user cannot input sensitive data that can potentially ruin the server. It keeps the user’s input separate from the SQL command, so the database treats it as plain data and not as code.
