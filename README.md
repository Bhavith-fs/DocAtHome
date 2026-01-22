# 🏥 DocAtHome
### Telemedicine & Real-Time Video Consultation Platform

<div align="center">

*Seamless virtual consultations for patients and doctors.*

[![HTML](https://img.shields.io/badge/HTML-5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](#)
[![CSS](https://img.shields.io/badge/CSS-3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](#)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](#)
[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](#)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](#)
[![WebRTC](https://img.shields.io/badge/WebRTC-RealTime-blue?style=for-the-badge)](#)

</div>

---

## 📖 Overview

**DocAtHome** is a comprehensive telemedicine platform that enables remote medical consultations via HD video and instant chat. Built using **PHP** and **WebRTC**, it offers a production-ready environment with secure signaling and TURN/STUN server support for **99.9% connectivity**.

---

## ✨ Key Features

### 🏥 For Patients
- **Easy Booking**: Streamlined appointment form with demo payment gateway.  
- **Virtual Waiting Room**: Auto-redirects when the doctor joins the session.  
- **HD Video**: Crystal clear audio/video with mute and camera controls.  
- **Mobile Ready**: Works perfectly on smartphones via secure HTTPS tunneling.  

### 👨‍⚕️ For Doctors
- **Live Dashboard**: View and manage incoming patient requests in real-time.  
- **Patient History**: Access booking details and medical notes during the call.  
- **Control Center**: Full management of calls, screen sharing, and patient status.  

---

## 🛠 Tech Stack

| Category | Technologies |
|----------|-------------|
| Frontend | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3 |
| Backend  | PHP 8.x, Apache 2.4 |
| Database | MySQL 8.x |
| Real-Time | WebRTC (RTCPeerConnection), SDP, ICE Candidates |

---

## 🚀 Quick Start

1. **Clone / Copy** the project folder to `C:\xampp\htdocs\DocAtHome`.  
2. **Start XAMPP**: Ensure Apache and MySQL are running.  
3. **Run Automatic Installation**: Double-click `setup.bat` to create the database and import tables automatically.  
4. **Launch**: Open your browser and visit [http://localhost/DocAtHome/](http://localhost/DocAtHome/).  

---

## 📂 Project Structure

```
video_call.php           — Core WebRTC and signaling logic
doctor_dashboard.php     — Doctor's control panel
chats/                   — Secure storage for signaling files
setup.bat                — One-click installation script
```


## 👤 Author

Bhavith Madhav  
Cybersecurity & Network Security Enthusiast
