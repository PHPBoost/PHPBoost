# Roadmap for PHPBoost CMS
Here is what the team thinks they are doing, subject to time or relevance.  
Please feel free to contribute to this list or contribute your own ideas.  

Ideas with a #number come from PHPBoost.com members [via the bugtracker](https://www.phpboost.com/bugtracker/unsolved/date/desc/)

## Administration
- Ability to upload an archive with several smilies
- Graphic personalization interface of the site, modification of the site colors
- log everything done in the administration

#### Menus
- Ability to associate a template with a menu to be able to customize it
- Ability to disable the display of a link in the menus
- Exclusion filters for menus

## Kernel
- Interface for viewing additional field values entered by members
- Managing asynchronous constraints in the form builder
- Advanced user alert system (javascript)
- Redesign of the event system and integration of a notification system

## User
- Review the way group subscribers are stored (currently stored in the member and groups table, make a join table)
- Ability for members to send private messages to all members of a group
- Ability for administrators to send private messages to all members
- Expiration of user accounts
- Ability to force one or all members to reset their password on next connection

#### Upload
1. **WIP** Redesign of upload interface and its operation, addition of Api
    1. **Done** Drag & drop
    1. **Done** Ability to upload several files in a row
    1. Upload with Ajax and progress bar
    1. Ability to modify file list before upload
    1. Ability to upload files in any folder
    1. New form field in Ajax to choose files without going through the popup

## Files
- Cleaning all language variables [Trello private doc](https://trello.com/c/Lrqy2NPq/86-lang)
- Image editor with js interface to resize, rotate, scale, etc.

## Comments
- Authorized links number in comments depending on user level (#1071)
- Moderation (#908)

## Installer
- Ability to choose modules, templates and languages to install

## Modules
- PDF Export (articles, wiki, pages).
- Store dates in database in ISO format rather than timestamp

#### Calendar
- Improve event registration (#1230)

#### Forum
- Ability to declare a nickname when posting as a visitor
- Subject Prefix (#1080)
- No-answer topic page (#1225)
- New global rights for groups with moderation on member messages
- Ability to create semi-automatic messages for moderators
- Ability of automatic shedding of subjects (configuration)
- Ability to define forbidden words (replacing by stars/dots)

#### Galerie
- Sorting by best/more on whole gallery and not only on a category
- Ability to download a picture
- Ability for automatic resizing with preview render
- Contribution with moderation
- Private gallery for members
- Slideshow on gallery index
- Ability to set an uplaod limit depending on groups

#### Search
- Keyword centralization system, tag cloud management.

#### Poll
- **WIP** Upgrade to MVC

#### Wiki
- System for recording articles by differences, line by line for a reduced footprint during successive editions
