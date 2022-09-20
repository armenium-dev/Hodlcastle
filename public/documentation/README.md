# Introduction
Welcome to phishmanager documentation page.

## What is phishmanager?
phishmanager is a phishing simulation and security awareness training platform. Our goal is to assist you in making your staff:
* understand security threats in a fun "learning by doing" kind of way.
* actively participate in identifying real threats for your business.
* take ownership of their responsibility and security hygiene.
* close the bridge between IT security departments and staff not involved with security on a day to day basis.

# Technical setup
phishmanager is a hosted solution so it does not require installation in itself, however several tasks need to be performed to make everything run smoothly:
## Whitelisting phishmanager
To ensure that our emails arrive please whitelist the following domains in your email server and spam filter:

`phishmanager.net`

`mailg.phishmanager.net`

## Phishmanager Outlook plugin
As part of the onboarding process we will provide you with the outlook plugin and correct configuration for your purposes.

# Getting started
A phishmanager representative will contact you and guide you through a checklist of activities to start off strong and involve staff in the correct way, if this hasn't happened yet then contact us at <a href="mailto:support@verifysecurity.nl">support@verifysecurity.nl</a>.

---
# Dry run (testing)
---
A dry run is a testing process where the effects of a possible failure are intentionally mitigated.

To do a dry run complete the following steps:
* In the "Recipients Groups" tab click on "Add new" and create a recipient group named "test-YOURCOMPANYNAME". Add only 1 or 2 email addresses belonging to admins of your corporate domain. Then click "Save".
* In the "Scenarios" tab select the Scenario "Dry run - testing only"
* Select the previously created recipient group "test-YOURCOMPANYNAME" and click on "Start Campaign".
* Monitor your inbox to see if the email arrives. If it doesn't arrive within minutes there is something wrong and you should check your spam folder and or spam filter. 


---
# Using phishmanager
---
## Dashboard
For those of us just starting with Phishmanager the Dashboard will still be empty, but over time it will get populated with launched phishing campaigns and statistics.

## Recipient Groups
A Recipient group is a list of one or more employees you want to target in a campaign. We recommend creating at least 2 recipient groups:
* The actual group with all employees.
* A group meant for troubleshooting purposes, containing only the email of 1 or 2 admins in your company. This is useful to make sure the spam filter is not blocking phishmanager emails. Campaigns/Scenario's sent to this recipient group can later be deleted from the dashboard.

## Email Templates
Create your own email or clone one of our public templates.
### Public email templates
We have a long list of email templates in different langues that you can choose from.

### Custom emails templates
Instead of starting from scratch it may be easier to clone an existing public email template using the green clone button.
![Cloning a public email template](/emailtemplate-clone.png)

Phishmanager uses a WYSIWYG editor. It is possible to change the HTML source code by clicking: `View > Source code`

![Changing HTML Source code](/emailtemplate-sourcecode.png )

1. Inside the Source code view it's possible to create a hyperlink for the click URL using the following code: `<a href="{{.URL}}">`
1. Please note that the WYSIWYG editor has a mind of it's own and will sometimes misrepresent things that will end up looking fine in the phishing email itself. One such example is the clicking URL, the WYSIWYG editor will seem to be injecting a string containing the email template number into the click URL `<a href="/emailTemplates/15/{{.URL}}">` this however is not the case and can be ignored.

## Source Domains
Source domains allow you to set the origin of the email in a campaign.
### Public Domains
Several generic public domains are available to choose from.

### Custom Domains
It is also possible to use a custom domain. For example if your corporate domain is acme.com it might be interesting to simulate a "typosquat domain" acnne.com and launch a campaign to see if your colleagues are be alert enough to detect impersonation. Only your company will be able to send emails using this domain.
* The first step is to purchase the domain, using a service like Namecheap.com or Godaddy for example.
* Set the A-record to point to the IP address of phishmanager.net, this is necessary in order to track clicks. Ping phishmanager.net from terminal on linux or CMD from Windows:
> ping phishmanager.net
* It may take a few hours for DNS servers to be updated.
* Make sure you have whitelisted the domain on your Email server so the emails from your campaign don't end up in SPAM folders.
* Do a dry run against your own email address before starting the campaign, just to test everything is in order.

<!--# Landings
If a recipient clicks on a link in an email he will be redirected to a landing page where the click will be registered for statistics. Landing pages can also be used to:
* Show a generic message informing the user this was a phishing exercise
* Alternatively in a more advanced scenario you can redirect the recipient to a website that looks just like a login page (for example Linkedin) and track if the recipient attempts to enter any data into a form or login page. For privacy and security reasons we do not capture any information that was typed in, instead we only track if the recipient tried to send the data to the webserver.-->

## Scenario's and Campaigns
Scenario's and Campaigns are nearly interchangeable. Here's the difference:
* Scenario's are the preferred method to launch phishing emails. Scenario's have been pre-built with your convenience in mind. After selecting a relevant scenario one only has to select the recipient group and launch the scenario. 
* Campaign's are just like scenaro's but they give the user more customization options. In a campaign you are able to:
 * Set a custom name for the campaign.
 * Select the email template
 * Select the sender domain
 * Optionally enable the URL  shortener
 * Select the FROM email address
 * Set a custom domain


<!--# ## Leaderboard
The Leaderboard provides a visual representation of the participants sorted by rank. -->

---
# FAQ
---
<!--## How do I add more users?-->

## How do I secure my account with Two-factor authentication (2FA)?
The use of 2FA is highly recommended for increased security, and 2FA  may actually become mandatory in the future. 
* On Android phones, Phishmanager supports both <a href="https://play.google.com/store/apps/details?id=org.fedorahosted.freeotp&hl=en_US&gl=USt">FreeOTP</a> and <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en_US&gl=US">Google Authenticator</a>

### Google Authenticator setup
After logging in to Phishmanager go to the right top of the screen and click on:
> YOURUsername > Profile > Enable 2FA

 * You will be shown a page with a QR-code. Make sure to take a screenshot and save and print this to paper. You need to do this to recover 2FA in the case that your smartphone would get lost or stolen. 
 * Scan the QR code with Google Authenticator
 * Log out from phishmanager and try to log in again. After entering the credentials Phishmanager will ask for the 2FA code.

## Beyond phishmanager?
Our company <a href="https://verifysecurity.nl/#contact">Verify Security</a> provides high quality penetration tests, live security awareness sessions and several other services. We are able to provide a discounted rate for phishmanager customers.

---
# Contact and support
---
If you have any questions that were not answered in the FAQ please contact us at <a href="mailto:support@verifysecurity.nl">support@verifysecurity.nl</a>


<!--1. First ordered list item
2. Another item
⋅⋅* Unordered sub-list. 
1. Actual numbers don't matter, just that it's a number
⋅⋅1. Ordered sub-list
4. And another item. -->
