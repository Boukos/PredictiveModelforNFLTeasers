from sklearn import preprocessing
from sklearn import linear_model
from sklearn import svm
from sklearn import neural_network
from sklearn import metrics 
import pandas as pd

##Away Team Teaser Training Data
trainset = pd.read_csv('python_train.csv',low_memory=False, usecols=['Season','Time','Timezone','home team','away team','home team line','Away Team Teaser'])
x_train = trainset.drop('Away Team Teaser', axis=1)
le_home = preprocessing.LabelEncoder()
le_away = preprocessing.LabelEncoder()
le_season = preprocessing.LabelEncoder()
le_timezone= preprocessing.LabelEncoder()
x_train['home team'] = le_home.fit_transform(x_train['home team'])
x_train['away team'] = le_away.fit_transform(x_train['away team'])
x_train['Timezone'] = le_timezone.fit_transform(x_train['Timezone'])
x_train['Season'] = le_season.fit_transform(x_train['Season'])
y_train = trainset['Away Team Teaser']

#Linear Regression Model
print ("Away Team Teaser Linear Regression")
linreg = linear_model.LinearRegression()
linreg.fit(x_train,y_train)
print (linreg.coef_)
score = linreg.score(x_train,y_train)
print (score)

#Testing Linear Regression Model
testset = pd.read_csv('python_test.csv',low_memory=False, usecols=['Season','Time','Timezone','home team','away team','home team line','Away Team Teaser'])
x_test = testset.drop('Away Team Teaser', axis=1)
x_test['home team'] = le_home.fit_transform(x_test['home team'])
x_test['away team'] = le_away.fit_transform(x_test['away team'])
x_test['Timezone'] = le_timezone.fit_transform(x_test['Timezone'])
x_test['Season'] = le_season.fit_transform(x_test['Season'])
y_test = testset['Away Team Teaser']
score = linreg.score(x_test,y_test)
print (score)

for i in linreg.predict(x_test):
    print (i)

#Support Vector Machine
print ("Away Team Teaser SVM")
svr = svm.SVR()
svr.fit(x_train, y_train)
testset = pd.read_csv('python_test.csv', low_memory=False, usecols=['Season','Time','Timezone','home team','away team','home team line','Away Team Teaser'])
x_test = testset.drop('Away Team Teaser', axis=1)
x_test['home team'] = le_home.fit_transform(x_test['home team'])
x_test['away team'] = le_away.fit_transform(x_test['away team'])
x_test['Timezone'] = le_timezone.fit_transform(x_test['Timezone'])
x_test['Season'] = le_season.fit_transform(x_test['Season'])
y_test = testset['Away Team Teaser']
preds = svr.predict(x_test)
print (metrics.mean_absolute_error(y_test, preds))
score = svr.score(x_test,y_test)
print (score)

#Neural Network
print ("Away Team Teaser Neural Network")
nn = neural_network.MLPRegressor()
nn.fit(x_train,y_train)
nn.predict(x_test)
score = nn.score(x_test,y_test)
print (score)

#Do not treat Season as Categorical
trainset = pd.read_csv('python_train.csv',low_memory=False, usecols=['Season','Time','Timezone','home team','away team','home team line','Away Team Teaser'])
x_train = trainset.drop('Away Team Teaser', axis=1)
le_home = preprocessing.LabelEncoder()
le_away = preprocessing.LabelEncoder()
le_timezone= preprocessing.LabelEncoder()
x_train['home team'] = le_home.fit_transform(x_train['home team'])
x_train['away team'] = le_away.fit_transform(x_train['away team'])
x_train['Timezone'] = le_timezone.fit_transform(x_train['Timezone'])
y_train = trainset['Away Team Teaser']

#Support Vector Machine
print ("Away Team Teaser SVM")
svr = svm.SVR()
svr.fit(x_train, y_train)
testset = pd.read_csv('python_test.csv', low_memory=False, usecols=['Season','Time','Timezone','home team','away team','home team line','Away Team Teaser'])
x_test = testset.drop('Away Team Teaser', axis=1)
x_test['home team'] = le_home.fit_transform(x_test['home team'])
x_test['away team'] = le_away.fit_transform(x_test['away team'])
x_test['Timezone'] = le_timezone.fit_transform(x_test['Timezone'])
y_test = testset['Away Team Teaser']
preds = svr.predict(x_test)
print (metrics.mean_absolute_error(y_test, preds))
score = svr.score(x_test,y_test)
print (score)

#Neural Network
print ("Away Team Teaser Neural Network")
nn = neural_network.MLPRegressor()
nn.fit(x_train,y_train)
nn.predict(x_test)
score = nn.score(x_test,y_test)
print (score)