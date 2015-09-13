TRUNCATE email_message;
INSERT INTO `email_message` (`email_message_code`, `to_email`, `subject`, `message`, `created_date`, `status`)
VALUES
('marketing_opportunity', 'dapo@cottacush.com', 'Opportunity to convert Express centre customer to corporate client', 'Hello Marketing Department,\r\n\r\nThere is an opportunity to convert the below stated detail to a corporate client.\r\n\r\nName: {{firstname}} {{lastname}}\r\nAddress: \r\n	{{street1}}\r\n	{{street2}}\r\n	{{city}}, {{state}}\r\n	{{country}}\r\nContact email : {{email}}\r\nContact phone number: {{phone}}\r\n\r\nKind regards,\r\nExpress Center Management Team', NOW(),1),
('staff_account_creation', 'dapo@cottasuch.com', 'Staff Account Creation', '<!DOCTYPE html>\n<html>\n<head>\n	<title></title>\n</head>\n<style type=\'text/css\'>\n	html {background-color: #ddd}\n	body {margin: 10px; color: #666; font-size: 16px; line-height: 22px; font-family: sans-serif}\n	#all {background-color: #fff; padding: 25px; margin: 0 0 5px}\n	h1, h2, h3, h4, h5, h6 {color: #333; margin: 10px 0 15px; line-height: 1.1}\n	a {color: #9C27B0}\n	p {margin-bottom: 10px}\n	header, footer, section {display:block;}\n	strong, b {font-weight: bold; color: #333;}\n	.form-group {margin-bottom: 10px}\n	label {font-weight: bold; color: #333;  margin-bottom: 2px}\n	.login-label {width: 90px;}\n	.form-control-static {padding: 3px 0}\n	section, .section {margin: 8px 0;}\n	.logo { width: 150px; display:block; margin: 0 auto 15px; }\n	.foot-note {color: #999; font-size:14px;line-height:1;text-align:center;}\n</style>\n<body>\n	<div id=\'all\'>\n		<header>\n			<img class=\'logo\' src=\'data:image/jpg;base64,/9j/4AAQSkZJRgABAQEBLAEsAAD/4RDuRXhpZgAATU0AKgAAAAgABAE7AAIAAAAMAAAISodpAAQAAAABAAAIVpydAAEAAAAYAAAQzuocAAcAAAgMAAAAPgAAAAAc6gAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAE9sYWppZGUgT3llAAAFkAMAAgAAABQAABCkkAQAAgAAABQAABC4kpEAAgAAAAMyOQAAkpIAAgAAAAMyOQAA6hwABwAACAwAAAiYAAAAABzqAAAACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAxNTowODoxNCAwNzo0MzozNAAyMDE1OjA4OjE0IDA3OjQzOjM0AAAATwBsAGEAagBpAGQAZQAgAE8AeQBlAAAA/+ELHmh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8APD94cGFja2V0IGJlZ2luPSfvu78nIGlkPSdXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQnPz4NCjx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iPjxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+PHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9InV1aWQ6ZmFmNWJkZDUtYmEzZC0xMWRhLWFkMzEtZDMzZDc1MTgyZjFiIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iLz48cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0idXVpZDpmYWY1YmRkNS1iYTNkLTExZGEtYWQzMS1kMzNkNzUxODJmMWIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyI+PHhtcDpDcmVhdGVEYXRlPjIwMTUtMDgtMTRUMDc6NDM6MzQuMjg3PC94bXA6Q3JlYXRlRGF0ZT48L3JkZjpEZXNjcmlwdGlvbj48cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0idXVpZDpmYWY1YmRkNS1iYTNkLTExZGEtYWQzMS1kMzNkNzUxODJmMWIiIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyI+PGRjOmNyZWF0b3I+PHJkZjpTZXEgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj48cmRmOmxpPk9sYWppZGUgT3llPC9yZGY6bGk+PC9yZGY6U2VxPg0KCQkJPC9kYzpjcmVhdG9yPjwvcmRmOkRlc2NyaXB0aW9uPjwvcmRmOlJERj48L3g6eG1wbWV0YT4NCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgPD94cGFja2V0IGVuZD0n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\'/>\n			<h2 class=\'title\'>Staff Account Creation</h2>\n		</header>\n		<div class=\'section\'>\n			<p>Hi {{name}},<br>Your Staff Account has been successfully created. Here are your details:</p>\n			<div class=\'form-group\'>\n				<strong class=\'login-label\'>Email:</strong> {{email}}\n			</div>\n			<div class=\'form-group\'>\n				<strong class=\'login-label\'>Password:</strong> {{password}}\n			</div>\n			<p>Please go to this link <a href=\'{{link}}\'>account</a> to verify your account and sign in.</p>\n			<br>\n			<p>Sincerely,<br>The CourierPlus Team</p>\n		</div>\n	</div>\n	<div class=\'foot-note\'>&copy; 2015 CourierPlus</div>\n</body>\n</html>',NOW(),1);
