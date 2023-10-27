## Tualo\Office\OnlineVote\States\failures\BockedUser

Der Status kann beim Login auftreten, dabei wird jeder 
Username in der Tabelle `username_count` mit einem 
Zeitstempel aufgezählt. ist der Konfigurationswert 
onlinevote/allowed_failures (Vorgabe 2) überschritten,
kann sich dieser User für die Zeit von 
onlinevote/block_minutes (Vorgabe 3) nicht einlogen.
Der Status Tualo\Office\OnlineVote\States\failures\BockedUser 
wird gesetzt.

## Tualo\Office\OnlineVote\States\failures\BallotPaperAllreadyVoted

Der Status kann in den Status Ballotpaper und BallotpaperOverview 
auftreten. Dabei kann dieser ausglöst werden, wenn der 
entsprechende Stimmzettel in der zwischenzeit im Remote- oder im 
Localensystem einen neuen Status erhalten hat.

## Tualo\Office\OnlineVote\States\failures\SessionBallotpaperSave

Der Status tritt auf, wenn ein Wähler sich mit einem weiteren 
Browser ein weiteres mal angemeldet hat. Nur eine aktive Sitzung 
ist im System je Wähler-ID erlaubt. die Informationen werden in der 
Tabelle `unique_voter_session` festgehalten.

## Tualo\Office\OnlineVote\States\failures\SessionInvalid

Der Status tritt auf, wenn ein Wähler sich mit einem weiteren 
Browser ein weiteres mal angemeldet hat. Nur eine aktive Sitzung 
ist im System je Wähler-ID erlaubt. die Informationen werden in der 
Tabelle `unique_voter_session` festgehalten.

## Tualo\Office\OnlineVote\States\failures\VoterLoginFailed

Der Status tritt ein, wenn der Benutzername oder das Passwort 
nicht stimmig ist.

## Tualo\Office\OnlineVote\States\failures\VoterLoginAllreadyOnline

Der Status tritt ein, wenn der Wähler für keinen weiteren Stimmzettel 
abstimmen darf und bereits Online gewählt hat.

## Tualo\Office\OnlineVote\States\failures\VoterLoginAllreadyOffline

Der Status tritt ein, wenn der Wähler für keinen weiteren Stimmzettel 
abstimmen darf und bereits per Brief gewählt hat.

## Tualo\Office\OnlineVote\States\failures\SystemBallotpaperSave

Der Status tritt auf, wenn beim Speichern eines Stimmzettels ein 
nicht planbarer Fehler auftritt.

## Tualo\Office\OnlineVote\States\failures\RemoteBallotpaperSave

Der Wählend Status konnte im Remotesystem nicht gesetzt werden.

## Tualo\Office\OnlineVote\States\failures\RemoteBallotpaperApi

Die Remote API konnte beim Speichern des Stimmzettels nicht 
erreicht werden.
