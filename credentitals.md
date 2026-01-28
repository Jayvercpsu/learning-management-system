**Admin Account:**
- Email: admin@lms.com
- Password: password
- Role: wala ra

**Teacher Account:**
- Email: teacher@lms.com
- Password: password
- Role: Teacher

**Student Account:**
- Email: student@lms.com
- Password: password
- Role: Student


## Features Overview

### Admin Features
- Approve/reject teacher registrations
- Manage all teachers and students
- View, edit, and delete user accounts
- Monitor system statistics
- Access all uploaded content

### Teacher Features
- Upload topics (PDF, Word, PowerPoint, Excel, CSV, Images)
- Upload video tutorials
- Create quizzes with multiple question types
- Grade student submissions
- View student quiz results
- Access student list
- Profile management

### Student Features
- Browse and download topics
- Watch video tutorials
- Access GeoGebra interactive graphing
- Take quizzes
- View quiz results
- Download results as PDF
- Profile management

## Quiz Features

### Question Types
1. **Multiple Choice** - Auto-graded
2. **True/False** - Auto-graded
3. **Essay** - Manual grading required

### Quiz Settings
- Set time limit (optional)
- Configure passing score
- Choose auto-check or manual grading
- Add images to questions
- Assign points per question

## File Upload Limits

- **Topics**: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, CSV, JPG, PNG, GIF (Max: 50MB)
- **Videos**: MP4, MOV, AVI, WMV (Max: 500MB)
- **Profile Pictures**: JPG, PNG, GIF (Max: 2MB)
- **Quiz Images**: JPG, PNG, GIF (Max: 2MB)

## Security Features

- Role-based access control
- CSRF protection
- Password hashing with bcrypt
- SQL injection prevention via Eloquent ORM
- XSS protection
- File upload validation
- Teacher approval system


heroku config:set APP_ENV=production --app learning-management-system
heroku config:set DB_CONNECTION=mysql --app learning-management-system
heroku config:set DB_HOST=tuy8t6uuvh43khkk.cbetxkdyhwsb.us-east-1.rds.amazonaws.com --app learning-management-system
heroku config:set DB_PORT=3306 --app learning-management-system
heroku config:set DB_DATABASE=z5ed5j3ltbloh6k0 --app learning-management-system
heroku config:set DB_USERNAME=a0mkkyixhg0yc4m1 --app learning-management-system
heroku config:set DB_PASSWORD=iql0j887ao3agqaq --app learning-management-system



heroku run mysql -u a0mkkyixhg0yc4m1 -p iql0j887ao3agqaq -h tuy8t6uuvh43khkk.cbetxkdyhwsb.us-east-1.rds.amazonaws.com z5ed5j3ltbloh6k0