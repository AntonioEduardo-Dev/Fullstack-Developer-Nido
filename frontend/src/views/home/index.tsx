import { useEffect, useState } from "react";
import Button from "../../components/button";
import apiUtils from "../../utils/apiUtils";
import { useLocation } from "react-router-dom";
import { Word } from "../../interface/WordInterface";

const Home = () => {
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const search = queryParams.get("search") || "";
  const limit = queryParams.get("limit") || "10"; // valor padrão
  const cursor = queryParams.get("cursor") || ""; // valor padrão

  const [activeTabIndex, setActiveTabIndex] = useState(0);
  const [word, setWord] = useState<Word[]>([]);
  const [words, setWords] = useState([]); // Estado para armazenar palavras
  const [history, setHistory] = useState([]); // Estado para armazenar histórico
  const [favorites, setFavorites] = useState([]); // Estado para armazenar favoritos
  const [currentIndex, setCurrentIndex] = useState(0);

  const handleNext = () => {
    if (currentIndex < word.length - 1) {
      setCurrentIndex(currentIndex + 1);
    }
  };

  const handlePrevious = () => {
    if (currentIndex > 0) {
      setCurrentIndex(currentIndex - 1);
    }
  };

  const currentWord = word[currentIndex];

  const loadData = async (endpoint: string, setter: any, post: {}) => {
    try {
      const response = await apiUtils(endpoint, 'get', post);
      if (response && response.results) {
        setter(response.results);
      }
    } catch (error) {
      console.log(error);
    }
  };

  const favorite = async (endpoint: string, method: string, body: {}, text: string) => {
    try {
      await apiUtils(endpoint, method, body);
      alert(`${text} como favorito.`);
    } catch (error) {
      console.log(error);
    }
  };

  const selectWord = async (word: string) => {
    if (!!word) {
      try {
        const response = await apiUtils(`/entries/en/${word}`, 'get', {});
        if (response && response.response) {
          setWord(response.response)
        }
      } catch (error) {
        console.log(error);
      }
    }
  };

  const unMarkFavorite = () => {
    const word = currentWord.word;
    favorite(`/entries/en/${word}/unfavorite`, 'delete', {}, "Desmarcado");
  };
  
  const markFavorite = () => {
    const word = currentWord.word;
    favorite(`/entries/en/${word}/favorite`, 'post', {}, "Marcado");
  };

  useEffect(() => {
    switch (activeTabIndex) {
      case 0:
        loadData('/entries/en', setWords, { limit, search, cursor });
        break;
      case 1:
        loadData('/user/me/history', setHistory, { limit, search, cursor });
        break;
      case 2:
        loadData('/user/me/favorites', setFavorites, { limit, search, cursor });
        break;
      default:
        break;
    }
  }, [activeTabIndex]); // Executa ao mudar a tab

  return (
    <div className="md:h-[90vh] sm:h-[100vh] flex justify-center items-center flex-1 z-0 p-5">
      <div className="md:h-auto sm:h-[100vh] grid lg:grid-cols-2 grid-cols-1 gap-4 bg-white rounded-lg sm:rounded-2xl py-14 px-6">
        <div className="md:columns-6 columns-12 flex flex-col items-center justify-start">
          {currentWord ? (
            <>
              <div className="min-w-72 min-h-32 rounded-xl border-2 border-gray-400 flex flex-col justify-center items-center gap-4">
                <span className="font-bold text-lg">{currentWord.word ?? ""}</span>
                <span className="font-semibold text-lg">{currentWord.phonetic ?? 'N/A'}</span>
              </div>
              {currentWord.phonetics.length > 0 && (
                <audio controls className="mt-5">
                  <source
                    src={
                      currentWord.phonetics[0]?.audio ||
                      currentWord.phonetics[1]?.audio ||
                      currentWord.phonetics[2]?.audio ||
                      ""
                    } type="audio/mpeg" />
                </audio>
              )}
              <div className="max-w-72 min-w-72 min-h-20 max-h-48 mb-6 overflow-y-auto">
                <span className="p-4">
                  {currentWord.meanings.map((meaning: any, index: string) => (
                    <div key={index}>
                      <strong>{meaning.partOfSpeech}</strong>
                      <ul>
                        {meaning.definitions.map((definition: any, defIndex: string) => (
                          <li key={defIndex}>
                            {definition.definition}
                            {definition.example && <em> (Ex: {definition.example})</em>}
                          </li>
                        ))}
                      </ul>
                    </div>
                  ))}
                </span>
              </div>
              <div className="min-w-72 flex flex-row justify-center items-center gap-4">
                <Button title="Voltar" onClick={handlePrevious} disabled={currentIndex === 0} />
                <Button title="Proximo" onClick={handleNext} disabled={currentIndex === word.length - 1} />
              </div>
              <div className="min-w-72 flex flex-row justify-center items-center gap-4">
                <Button title={true ? "Favoritar" : "Desfavoritar"} onClick={markFavorite} disabled={false} />
                <Button title={true ? "Desfavoritar" : "Favoritar"} onClick={unMarkFavorite} disabled={false} />
              </div>
            </>
          ) : (
            <span className="font-bold text-lg">Não há dados disponíveis</span>
          )}
        </div>
        <div className="md:columns-6 columns-12 flex flex-col items-center justify-start">
          <div className="min-w-full min-h-10 flex justify-center items-center gap-4 p-2">
            <button
              className={`${0 === activeTabIndex ? "bg-blue-600" : "bg-blue-500"} py-2 px-4 rounded-lg text-white font-semibold text-xs`}
              onClick={() => setActiveTabIndex(0)}>
              Word list
            </button>
            <button
              className={`${1 === activeTabIndex ? "bg-blue-600" : "bg-blue-500"} py-2 px-4 rounded-lg text-white font-semibold text-xs`}
              onClick={() => setActiveTabIndex(1)}>
              History
            </button>
            <button
              className={`${2 === activeTabIndex ? "bg-blue-600" : "bg-blue-500"} py-2 px-4 rounded-lg text-white font-semibold text-xs`}
              onClick={() => setActiveTabIndex(2)}>
              Favorites
            </button>
          </div>
          <div data-tab-content="">
            <div id="words"
              className={`md:w-96 w-full h-full border-2 mt-6 p-4 rounded-lg border-gray-400 grid grid-cols-4 gap-1	
              ${0 === activeTabIndex ? '' : 'hidden'}`}
              style={{ maxHeight: '400px', overflowY: 'auto' }}>
              {words.map((word, index) => (
                <span key={index} className="w-13 h-10 py-2 px-1 border-[1px] border-b-2 rounded-md border-gray-400 
                  flex justify-center items-center cursor-pointer overflow-hidden text-ellipsis whitespace-nowrap"
                  onClick={() => selectWord(word)}>
                  {word}
                </span>
              ))}
            </div>
            <div id="history"
              className={`md:w-96 w-full h-full border-2 mt-6 p-4 rounded-lg border-gray-400 grid grid-cols-4 gap-1	
              ${1 === activeTabIndex ? '' : 'hidden'}`}>
              {history.map((element: any, index) => (
                <span key={index} className="w-13 h-10 py-2 px-1 border-[1px] border-b-2 rounded-md border-gray-400 
                  flex justify-center items-center cursor-pointer overflow-hidden text-ellipsis whitespace-nowrap"
                  onClick={() => selectWord(element.word)}>
                  {element.word}
                </span>
              ))}
            </div>
            <div id="favorites"
              className={`md:w-96 w-full h-full border-2 mt-6 p-4 rounded-lg border-gray-400 grid grid-cols-4 gap-1	
              ${2 === activeTabIndex ? '' : 'hidden'}`}>
              {favorites.map((element: any, index) => (
                <span key={index} className="w-13 h-10 py-2 px-1 border-[1px] border-b-2 rounded-md border-gray-400 
                  flex justify-center items-center cursor-pointer overflow-hidden text-ellipsis whitespace-nowrap"
                  onClick={() => selectWord(element.word)}>
                  {element.word}
                </span>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;
