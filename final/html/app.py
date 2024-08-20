# app.py

from flask import Flask, render_template, jsonify
import pywhatkit as kit
from datetime import datetime

app = Flask(__name__)

@app.route('/navbar')
def navbar():
    # Render the navbar.html template
    return render_template('navbar.html')

@app.route('/send_sos_message', methods=['GET'])
def send_sos_message():
    # Replace these values with the recipient's phone number and your message
    phone_number = "+123456789"  # Replace with your recipient's phone number
    message = "SOS! This is an emergency."

    # Get the current time
    now = datetime.now()
    current_hour = now.hour
    current_minute = now.minute  # Add 1 minute to ensure it's sent in the next minute

    # Send the message immediately
    kit.sendwhatmsg(phone_number, message, current_hour, current_minute)

    return jsonify({"result": "SOS message sent!"})

if __name__ == '__main__':
    app.run(debug=True)
