/**
 * @copyright  2019 Maksud R
 * @package   mod_attendance
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class AttendanceQRCode {
  constructor() {
    this.sessionId = 0;
    this.qrCodeInstance = "";
    this.qrCodeHTMLElement = "";
    this.studentId = 0;
  }

  start(sessionId, qrCodeHTMLElement, studentId) {
    this.sessionId = sessionId;
    this.qrCodeHTMLElement = qrCodeHTMLElement;
    this.studentId = studentId;
    console.log('Student ID:', this.studentId); // imprime o valor do ID do aluno no console
    this.qrCodeSetUp();
    this.generateAndShowQRCode();
  }

  qrCodeSetUp() {
    this.qrCodeInstance = new QRCode(this.qrCodeHTMLElement, {
      text: "",
      width: 300,
      height: 300,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H,
    });
  }

  generateQRCode(studentId) {
    const qrcodeurl = `attendance.php?qrpass=${studentId}&sessid=${this.sessionId}&studentid=${this.studentId}`;
    this.qrCodeInstance.clear();
    this.qrCodeInstance.makeCode(qrcodeurl);
  }

  generateAndShowQRCode() {
    this.qrCodeSetUp();
    this.generateQRCode(this.studentId);
  }
  

  fetchPasswords() {
    return fetch(
      `password.php?session=${this.sessionId}&returnpasswords=1`,
      {
        headers: {
          "Content-Type": "application/json; charset=utf-8",
        },
      }
    ).then((resp) => resp.json());
  }

  startRotating() {
    setInterval(() => {
      this.fetchPasswords()
        .then((data) => {
          const password = data.find(
            (element) => element.studentid === this.studentId
          ).password;
          this.generateQRCode(password);
        })
        .catch((err) => {
          console.error("Error fetching QR passwords from API.");
          location.reload(true);
        });
    }, 1000);
  }
}
