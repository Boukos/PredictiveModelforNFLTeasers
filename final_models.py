from sklearn import linear_model
from sklearn import svm
from sklearn.model_selection import train_test_split
import numpy as np
import pandas as pd

#Away Team Teaser Linear Regression
trainset = pd.read_csv('teasers_underdog.csv',low_memory=False)
train_set = trainset.drop('Wins', axis=1)
x_train = train_set.drop('Totals', axis=1)
x= x_train.drop('Ratio', axis=1)

y= trainset['Ratio'].astype(np.int)
X_train, X_test, Y_train, Y_test = train_test_split(x, y, test_size=.4, random_state=0)

#Logistic Regression
print ("Logistic Regression")
logreg = linear_model.LogisticRegression()
logreg.fit(X_train,Y_train)
print ('R^2 score')
print (logreg.score(X_test, Y_test))
print ("coefficients")
print (logreg.coef_)

#SVM
svr = svm.SVR()
svr.fit(X_train, Y_train)
print ('SVR R^2 score')
print (svr.score(X_test,Y_test))

#stochastic gradient descent
print ("SGD Regression")
sgd = linear_model.SGDRegressor()
sgd.fit(X_train, Y_train)
print ('SGD R^2 score')
print (sgd.score(X_test,Y_test))

#perceptron 
print ("Perceptron")
perceptron = linear_model.Perceptron()
perceptron.fit(X_train, Y_train)
print ('perceptron R^2 score')
print (perceptron.score(X_test,Y_test))


