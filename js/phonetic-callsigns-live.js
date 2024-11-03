$(document).ready(function() {
    'use strict';

    const phoneticAlphabet = {
        'A': 'Alpha', 'B': 'Bravo', 'C': 'Charlie', 'D': 'Delta', 'E': 'Echo', 'F': 'Foxtrot',
        'G': 'Golf', 'H': 'Hotel', 'I': 'India', 'J': 'Juliet', 'K': 'Kilo', 'L': 'Lima',
        'M': 'Mike', 'N': 'November', 'O': 'Oscar', 'P': 'Papa', 'Q': 'Quebec', 'R': 'Romeo',
        'S': 'Sierra', 'T': 'Tango', 'U': 'Uniform', 'V': 'Victor', 'W': 'Whiskey', 'X': 'X-ray',
        'Y': 'Yankee', 'Z': 'Zulu',
        '0': 'Zero', '1': 'One', '2': 'Two', '3': 'Three', '4': 'Four', '5': 'Five',
        '6': 'Six', '7': 'Seven', '8': 'Eight', '9': 'Nine'
    };

    function getPhonetic(callsign) {
        return callsign.split('').map(char => phoneticAlphabet[char.toUpperCase()] || char).join(' ');
    }

    function addPhoneticLabels() {
        $('.oc_call').each(function() {
            const callsignSpan = $(this);
            const callsign = callsignSpan.text().trim();
            if (callsign && callsignSpan.next('.phonetic-label').length === 0) {
                const phonetic = getPhonetic(callsign);
                const phoneticElement = $('<div></div>')
                    .addClass('phonetic-label')
                    .css({
                        'font-size': '3em',
                        'color': '#0000FF',
                        'text-align': 'left'
                    })
                    .html(phonetic);

                callsignSpan.parent().append(phoneticElement);
            }
        });
    }

    addPhoneticLabels();

    const observer = new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            if (mutation.type === 'childList' || mutation.type === 'subtree') {
                addPhoneticLabels();
            }
        }
    });

    observer.observe(document.getElementById('liveDetails'), { childList: true, subtree: true });
});
