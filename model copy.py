import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.svm import LinearSVC
from sklearn.metrics import accuracy_score
import mysql.connector as msql
from mysql.connector import Error

try:
    conn = msql.connect(host='localhost', user='admin', password='123', db='lielien')
    if conn.is_connected():
        cursor = conn.cursor()
        query = "SELECT nama_produk, deskripsi_produk, kategori FROM produk"
        cursor.execute(query)
        results = cursor.fetchall()

        # Convert the results into a DataFrame
        df = pd.DataFrame(results, columns=['nama_produk', 'deskripsi_produk', 'kategori'])

        # Drop rows with None values in the 'kategori' column
        df = df.dropna(subset=['kategori'])

        # Split the dataset into X and y
        X = df[['nama_produk', 'deskripsi_produk']]
        y = df['kategori']

        # Split the data into train and test sets
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.25, random_state=0)

        # Create a TF-IDF vectorizer and fit it on the training data
        vectorizer = TfidfVectorizer()
        X_train_tfidf = vectorizer.fit_transform(X_train['nama_produk'] + ' ' + X_train['deskripsi_produk'])
        X1 = X_train_tfidf[0].toarray()[0]
        # Transform the test data using the fitted vectorizer
        X_test_tfidf = vectorizer.transform(X_test['nama_produk'] + ' ' + X_test['deskripsi_produk'])

        # Create a LinearSVC classifier and train it on the TF-IDF transformed training data
        classifier = LinearSVC(random_state=0)
        classifier.fit(X_train_tfidf, y_train)

        # Make predictions on the training set
        y_train_pred = classifier.predict(X_train_tfidf)

        # Make predictions on the test set
        y_pred = classifier.predict(X_test_tfidf)

        # Save the classifier and vectorizer
        import pickle
        with open('model.pkl', 'wb') as file:
            pickle.dump(classifier, file)
        with open('vectorizer.pkl', 'wb') as file:
            pickle.dump(vectorizer, file)
        w=classifier.coef_
        b=classifier.intercept_
        # Calculate metrics
        X1 = X_train_tfidf[0].toarray()[0]
        X2 = X_test_tfidf[0].toarray()[0]
        w1 = w[0][0]
        w2 = w[0][1]

        print("Nilai X1:",X1)
        print("Nilai X2:",X2)
        print("Nilai w1:",w1)
        print("Nilai w2:",w2)
        print("Nilai B:",b)

        from sklearn.metrics import f1_score, confusion_matrix

        # Calculate F1 score
        f1 = f1_score(y_test, y_pred, average='weighted')
        print("F1 Score:", f1)

        # Calculate confusion matrix
        cm = confusion_matrix(y_test, y_pred)
        print("Confusion Matrix:")
        print(cm)

                # Calculate accuracy
        accuracy = accuracy_score(y_test, y_pred)
        print("Accuracy:", accuracy)

        # Calculate accuracy score for training set
        train_accuracy = accuracy_score(y_train, y_train_pred)

# Make predictions on the test set
        test_accuracy = accuracy_score(y_test, y_pred)

        print("Train Accuracy:", train_accuracy)
        print("Test Accuracy:", test_accuracy)  
        # Close the cursor and the connection
        cursor.close()
        conn.close()
    else:
        print("Failed to connect to MySQL.")
except Error as e:
    print("Error while connecting to MySQL", e)
