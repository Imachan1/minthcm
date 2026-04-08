pracujemy nad legacy/MintCLI/src/Commands/Upgrade.php

Rzeczy do wykonania
- [x] Przygotować upgrade do wersji 4.3.0. Bazując na wiedzy zawartej w releaseNotes/ReleaseNote-4.3.md przygotuj nowy plan w osobnym pliku.
- [ ] Jeżeli podnoszę sie do wersji 4.3.1 a nie ma upgrade 4.3.0. To system musi sprawdzić czy pomiędzy aktualną wersją, np. 4.2.0 a 4.3.1 są jakieś upgrade. W naszym waypadku jest to 4.3.0. To w takiej sytuacji powinien przeskoczyć do brancha 4.3.1 ale odpalić upgrade 4.3.0. Jeżeli pomiędzy wersjami są dwa lub więcej upgrade -> wtedy nie da się zrobić aktualizacji. Należy przeskoczyć na wersje po pierwszym upgrade a potem na kolejną i kolejną aż do docelowej.