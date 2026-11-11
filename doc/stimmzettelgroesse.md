# JSON-Stimmzettelgröße bei RSA-OAEP (4096 Bit, SHA-256)

Dieses Dokument beschreibt, wie groß ein Stimmzettel (JSON) maximal sein darf,
wenn er direkt mit RSA-OAEP verschlüsselt wird — mit den Parametern, die in
`src/js/lazy/controller/Ballotbox.js` verwendet werden:

```js
{
  name: "RSA-OAEP",
  modulusLength: 4096,
  publicExponent: new Uint8Array([1, 0, 1]),
  hash: "SHA-256",
}
```

## Maximale Klartextgröße

Bei RSA-OAEP gilt für die maximale Klartextlänge:

```
maxKlartext = k − 2·hLen − 2
```

| Parameter | Wert |
|---|---|
| Modulus `k` (4096 Bit) | 512 Bytes |
| Hashlänge `hLen` (SHA-256) | 32 Bytes |
| OAEP-Overhead (`2·hLen + 2`) | 66 Bytes |
| **Maximale Klartextgröße** | **446 Bytes** |

Der verschlüsselte Stimmzettel (Chiffrat) ist unabhängig von der
Klartextlänge immer genau **512 Bytes** groß.

## Format des Stimmzettels

Der Stimmzettel wird als JSON-Liste von Integern (Kandidaten-IDs) abgelegt,
ohne Leerzeichen (entspricht `JSON.stringify`):

```json
[1,23,456]
```

Die maximale Kandidaten-ID ist **dreistellig** (≤ 999).

## Größenberechnung

Für `n` Kandidaten-IDs ergibt sich die Bytegröße (UTF-8, nur ASCII-Ziffern,
Kommas und Klammern — 1 Byte pro Zeichen):

```
Größe = 2 (Klammern) + Summe(Ziffern je ID) + (n − 1) (Kommas)
```

Worst Case (alle IDs dreistellig):

```
Größe = 2 + 3·n + (n − 1) = 4·n + 1
```

### Maximale Anzahl an IDs pro Stimmzettel

| IDs im Stimmzettel | Bytes pro ID | Formel | max. Anzahl `n` (≤ 446 Bytes) |
|---|---|---|---|
| alle dreistellig (Worst Case) | 3 | `4n + 1 ≤ 446` | **111** |
| alle zweistellig | 2 | `3n + 1 ≤ 446` | 148 |
| alle einstellig | 1 | `2n + 1 ≤ 446` | 222 |

**Verbindliche Obergrenze:** Da IDs bis zu dreistellig sein können, dürfen
pro Stimmzettel maximal **111 Kandidaten-IDs** abgelegt werden
(Worst Case: `4·111 + 1 = 445 Bytes ≤ 446 Bytes`).

Bereits bei 112 dreistelligen IDs (`449 Bytes`) schlägt die Verschlüsselung
mit einem `OperationError` fehl.

## Hinweis für größere Stimmzettel

Sollten künftig mehr als 111 IDs pro Stimmzettel benötigt werden, muss auf
ein hybrides Verfahren umgestellt werden (z. B. AES-GCM für den Stimmzettel,
RSA-OAEP nur für den AES-Schlüssel). Damit entfällt die Größenbeschränkung
des Klartexts.
