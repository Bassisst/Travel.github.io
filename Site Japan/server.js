// server.js
const express = require("express");
const mongoose = require("mongoose");
const bodyParser = require("body-parser");
const bcrypt = require('bcryptjs');
const jwt = require("jsonwebtoken");
const nodemailer = require('nodemailer');
const cookieParser = require('cookie-parser'); // Dodajemy cookie-parser do obsługi ciasteczek

const app = express();
const PORT = 3000;
const transporter = nodemailer.createTransport({
    host: "smtp.zoho.eu",
    port: 465,
    secure: true,
    auth: {
        user: 'vova30511@gmail.com', // Zastąp swoją nazwą użytkownika Yahoo
        pass: '1q2w3e4r5' // Zastąp hasłem aplikacji Yahoo
    }
});
mongoose.connect("mongodb+srv://Bassist:1q2w3e4r5@cluster0.mnome.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0  ");

// Definicja schematu użytkownika
const userSchema = new mongoose.Schema({
    username: String,
    password: String,
    email: String,
    name: String,         // Imię
    lastname: String,     // Nazwisko
    gender: String,       // Płeć
    address: String,      // Adres
    bio: String           // Biografia
});

const User = mongoose.model("User", userSchema);
const SECRET_KEY = "your_secret_key"; // Klucz do generowania tokenów JWT

// Middleware do obsługi JSON
app.use(bodyParser.json());
// Ustawienie katalogu głównego do plików statycznych
app.use(express.static(__dirname));
// Trasa logowania
app.use(cookieParser()); // Do obsługi ciasteczek

// Trasa logowania
app.post("/login", async (req, res) => {
    const { username, password } = req.body;

    if (!username || !password) {
        return res.status(400).json({ message: "Wpisz swoją nazwę użytkownika i hasło" });
    }

    try {
        const user = await User.findOne({ username });
        if (user) {
            const isMatch = await bcrypt.compare(password, user.password);
            if (isMatch) {
                const token = jwt.sign({ username: user.username }, SECRET_KEY); // Generujemy token JWT

                // Ustawiamy token jako cookie httpOnly
                res.cookie("authToken", token, {
                    httpOnly: true,
                    secure: true, // Ustaw na true dla HTTPS
                    maxAge: 7 * 24 * 60 * 60 * 1000 // 7 dni w milisekundach
                });

                return res.status(200).json({ message: "Pomyślne logowanie" });
            } else {
                return res.status(401).json({ message: "Nieprawidłowe hasło" });
            }
        } else {
            return res.status(401).json({ message: "Nie znaleziono użytkownika" });
        }
    } catch (error) {
        return res.status(500).json({ message: "Błąd serwera" });
    }
});

// Middleware do weryfikacji tokena z ciasteczek
const authenticateToken = (req, res, next) => {
    const token = req.cookies.authToken;
    if (!token) return res.sendStatus(401);

    jwt.verify(token, SECRET_KEY, (err, user) => {
        if (err) return res.sendStatus(403);
        req.user = user;
        next();
    });
};

// Dodajemy to middleware dla tras, które wymagają autoryzacji
app.use(authenticateToken);

// Trasa rejestracji
app.post("/register", async (req, res) => {
    const { username, password, email } = req.body;
    const user = await User.findOne({ username });
    if(user){
        return res.status(400).json({ message: "Tak użytkownik już istnieje" });
    }
    if (!username || !password || !email) {
        return res.status(400).json({ message: "Wpisz swoją nazwę użytkownika, hasło oraz adres e-mail" });
    }

    try {
        const hashedPassword = await bcrypt.hash(password, 10);
        const newUser = new User({ username, password: hashedPassword, email });
        await newUser.save();

        // Wysyłanie powiadomienia na e-mail
        const mailOptions = {
            from: 'vova30511@gmail.com',
            to: email,
            subject: 'WorldOfJapan',
            html: `
  `,
        };
        transporter.sendMail(mailOptions, function(error, info) {
            if (error) {
                console.log(error);
            } else {
                console.log('Email wysłany: ' + info.response);
            }
        });

        res.status(201).json({ message: "Użytkownik zarejestrowany pomyślnie" });
    } catch (error) {
        res.status(500).json({ message: "Błąd serwera" });
    }
});



app.listen(PORT, () => {
    console.log(`Serwer uruchomiony na http://localhost:${PORT}`);
});