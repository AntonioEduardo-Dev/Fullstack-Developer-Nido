interface Phonetic {
  audio?: string;
}

interface Definition {
  definition: string;
  example?: string;
}

interface Meaning {
  partOfSpeech: string;
  definitions: Definition[];
}
export interface Word {
    word: string;
    phonetic?: string;
    phonetics?: Phonetic[];
    meanings: Meaning[];
}