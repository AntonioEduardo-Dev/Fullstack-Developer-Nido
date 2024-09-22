
export interface Word {
    word: string;
    phonetic?: string;
    phonetics: { audio: string }[];
    meanings?: any;
}