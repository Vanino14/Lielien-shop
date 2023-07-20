import pickle

# Load the trained model
with open('model.pkl', 'rb') as file:
    model = pickle.load(file)

# Function to preprocess the input data
def preprocess_input(nama_produk, deskripsi_produk):
    input_data = [nama_produk + ' ' + deskripsi_produk]
    return input_data

# Function to make predictions
def predict(nama_produk, deskripsi_produk):
    # Preprocess the input data
    input_data = preprocess_input(nama_produk, deskripsi_produk)

    # Make predictions using the loaded model
    predictions = model.predict(input_data)

    return predictions

# Example usage
nama_produk = 'Gelang ajaib'
deskripsi_produk = 'Deskripsi gelang ajaib'

predictions = predict(nama_produk, deskripsi_produk)
print(predictions)
