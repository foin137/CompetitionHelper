# CompetitionHelper
Helps to assign points for different challenges in a competition.

# What does this program do?
It helps you organise a competition.

For example, if you want to have a competition at a birthday party with 3 challenges:

1) Run as fast as you can to some point
2) Juggle 3 balls for as long as you can
3) Some other challenge where you can obtain points.

Now, after having done all challenges, you might ask, which of the participants is the overall winner. What if for one challenge the maximum number of points was 100 and for another only 20. How to weigh them properly?
That's what this program/website can do. It takes all participant scores from one challenge, categorizes it and then assigns points.

# Features
- Create competitions
- Automatically assign points
- Manage number of point categories
- Manage number of participants and challenges
- Add score all at once or for every challenge individually
- Automatically recalculate points during the competition
- Every participant can look up the current leaderboard
- Different options for assigning points: More points / Less points / more time / less time.
- English or German interface

# Installation
The program is written in php, so you will need a php parser on your webserver.

You need a database, which can be any name you want, e.g. "competition".

Rename 'includes.example.php' to 'includes.php' and insert the corresponding user id, password, host name and database name (in our case "competition").

Import normal.sql into the database (this contains data on the normal distribution, which is needed to assign categories).

# How does it work?

1. To create a competition, click "Create competition" and enter the competition name and number of categories. The categories are evenly spaced by the program.
2. Choose points for every category. You can enter if you have staff for every challenge that manually enters the results for the participants.
3. Add challenges
4. Add participants
5. In the control center you can edit all aspects of the competition.

# CompetitionHelper - Wettbewerbshelfer
Hilft dabei, Punkte zu verschiedenen Challenges oder Stationen im Rahmen eines Wettbewerbs zuzuteilen.

# Was macht dieses Programm?
Es hilft dabei, einen Wettbewerb zu bewerten.

Zum Beispiel, wenn man einen Wettbewerb bei einer Geburtstagsparty geplant hat mit den Stationen:

1) Wettlauf
2) Mit 3 Bällen jonglieren solange man kann
3) Irgendeine andere Aufgabe, für die es Punkte gibt

Nach all diesen Aufgaben fragt man sich, welche*r Teilnehmer*in denn nun gewonnen hat ... Was, wenn es für eine Aufgabe 100 Punkte zu erreichen gab, für eine andere aber maximal nur 20? Wie gewichtet man die einzelnen Aufgaben richtig (oder fair)?
Das ist genau, wobei dieses Programm hilft. Es nimmt alle Resultate für eine Station/Challenge und berechnet daraus einen Punkteschlüssel, sodass es bei jeder Station gleich viele Punkte zu holen gibt.

# Features
- Erstellung von Wettbewerben
- Automatische Punkteberechnung
- Variable Anzahl von Punktekategorien und einstellbare Punkte pro Kategorie
- Variable Anzahl von Stationen und Teilnehmer
- Zentrale Eingabe aller Punkte oder einzelne Stationenbetreuer, die mit Passwort die Punkte für ihre Station eingeben können
- Automatische Aktualisierung der Punkte während des Wettbewerbs
- Jeder Teilnehmer kann über die Website verfolgen, wie die aktuelle Rangliste ist und wie die Kategorien bei den einzelnen Stationen eingeteilt wurden.
- Verschiedene Optionen für die automatische Punktevergabe: Wie viel Prozent ca. in der obersten Kategorie landen sollen, ob die oberste Kategorie immer besetzt sein soll, ob die Kategorieeinteilung nach unten korrigiert werden soll


# Installation
Das Programm ist in php geschrieben, daher benötigt man einen php parser auf dem Webserver.

Man benötigt eine Datenbank mit beliebigem Namen, z.b. "wettbewerb".

Benenne "includes.example.php" in "includes.php" um und füge die korrekten Daten zu Benutzername, Passwort, Host und Datenbankname ein (in unserem Fall heißt die Datenbank dann "wettbewerb").

Importiere "normal.sql" als Tabelle in die Datenbank, diese Tabelle enthält Daten über die Normalverteilung, die für die Kategoriezuteilung gebraucht wird. Der Import kann zum Beispiel mittels phpmyadmin erfolgen.

# Wie funktioniert's?

1. Um einen Wettbewerb zu erstellen, klickt man erst auf "Wettbewerb erstellen", dann gibt man den Namen des Wettbewerbs und die anzahl der Kategorien ein.
    Kategorien heißt hier zum Beispiel:
    Kat 1: 1 Punkt
    Kat 2: 2 Punkte
    Kat 3: 3 Punkte
    Kat 4: 5 Punkte
    Die Kategorien werden dann vom Programm für jede Station gleichmäßig verteilt. So kann man steuern, dass die besten bei jeder Station mehr Punkte bekommen, natürlich können auch verschiedene Kategorien gleich viele Punkte geben.

2. Danach kann man die Punkte für die Kategorien auswählen.
    Möchte man Stationenbetreuer, die selbst die Punkte ihrer Station eintragen können (mit Passwort), so kann man das auch hier auswählen. Wenn die Punkte laufend aktualisiert werden sollen (etwa damit die Teilnehmer immer den aktuellen Stand sehen können), wählt man "Die Punkte werden bei jeder Eingabe neu berechnet" (Wird aber auch nicht zu oft gemacht, ich denke höchstens alle 30 Sekunden)

    Unter den erweiterten Optionen findet man
    a) Wie viele Prozent der Teilnehmer in die höchste Kategorie gereiht werden sollen
    b) Ob die höchste Kategorie immer vergeben werden muss. Es kann manchmal vorkommen, dass die höchste Kategorie gar nicht vergeben wird, dafür ist diese Option da.
    c) Mittelwert jeder Station um so viel Prozent zum Schlechteren korrigieren: Damit werden alle tendenziell in eine bessere Kategorie gereiht

    Um später den Wettbewerb noch bearbeiten zu können, muss man auch ein Passwort eingeben.

3. Dann bekommt man die Möglichkeit Stationen hinzuzufügen, wobei man zu jeder Station auch eine Info eingeben kann, die allerdings nur der Admin sehen kann. Hier kann bzw. muss man auch ein Passwort setzen für die Stationenbetreuer. Damit können sich die Stationenbetreuer dann einloggen und die Punkte eingeben.
    Man kann auch eingeben, ob eine hohe oder niedrige Punktezahl bzw. Zeit besser ist. Wählt man "Wertung wird nicht berechnet" aus, so werden für diese Station immer die eingetragenen Punkte verwendet und keine Kategorien berechnet.
4. Danach kann man Teilnehmer hinzufügen und wieder eine Info eingeben, die nur der Admin sehen kann.
5. Schließlich kommt man zum Kontrollcenter, wo man alle Einstellungen nochmals bearbeiten kann, sowie weitere Stationen und Teilnehmer hinzufügen kann. Stationen bzw. Teilnehmer kann man allerdings nicht löschen, sondern nur als inaktiv schalten (deaktivieren)
