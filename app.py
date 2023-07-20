import pickle
import mysql.connector
from flask import Flask, jsonify, request
import datetime


app = Flask(__name__)

# Load the trained model
with open('model.pkl', 'rb') as file:
    model = pickle.load(file)
with open('vectorizer.pkl', 'rb') as file:
    vectorizer = pickle.load(file)

# Membuat koneksi ke basis data MySQL
conn = mysql.connector.connect(
    host='localhost',
    user='admin',
    password='123',
    database='lielien'
)
cursor = conn.cursor()

# Route untuk menerima permintaan POST dan memberikan respons JSON
@app.route('/predict', methods=['POST'])
def predict():
    # Menerima data input dari permintaan POST
    data = request.json

    # Proses data menggunakan model dan vectorizer
    nama_produk = data['nama_produk']
    deskripsi_produk = data['deskripsi_produk']

    # Preprocess the input data
    input_data = [nama_produk + ' ' + deskripsi_produk]
    input_data = vectorizer.transform(input_data).reshape(1, -1)

    # Make predictions using the loaded model
    predictions = model.predict(input_data)

    # Membuat respons JSON
    response = {
        'nama_produk': nama_produk,
        'deskripsi_produk': deskripsi_produk,
        'prediction': predictions[0]
    }

    # Mendapatkan tanggal dan waktu saat ini
    tanggal_prediksi = datetime.date.today()

    # Update basis data dengan hasil prediksi
    query = "UPDATE produk SET prediksi = %s, tanggal_prediksi = %s WHERE nama_produk = %s AND deskripsi_produk = %s"
    values = (predictions[0], tanggal_prediksi, response['nama_produk'], response['deskripsi_produk'])
    cursor.execute(query, values)
    conn.commit()

    # Mengembalikan respons JSON
    return jsonify(response)

if __name__ == '__main__':
    app.run(port=8000)

# Menutup koneksi ke basis data
cursor.close()
conn.close()
