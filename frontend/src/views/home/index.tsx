import { useEffect, useState } from "react";
import Button from "../../components/button";
import apiUtils from "../../utils/apiUtils";
import { useLocation } from "react-router-dom";
import { Word } from "../../interface/WordInterface";

interface PostData {
  limit?: string;
  search?: string;
  cursor?: string;
}

const Home = () => {
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const search = queryParams.get("search") || "";
  const limit = queryParams.get("limit") || "20"; // valor padrão

  const [activeTabIndex, setActiveTabIndex] = useState(0);
  const [word, setWord] = useState<Word[]>([]);
  const [words, setWords] = useState([]); // Estado para armazenar palavras
  const [history, setHistory] = useState([]); // Estado para armazenar histórico
  const [favorites, setFavorites] = useState([]); // Estado para armazenar favoritos
  const [cursorWords, setCursorWords] = useState(""); // Cursor para palavras
  const [cursorHistory, setCursorHistory] = useState(""); // Cursor para histórico
  const [cursorFavorites, setCursorFavorites] = useState(""); // Cursor para favoritos
  const [currentIndex, setCurrentIndex] = useState(0);
  const [currentCursor, setCurrentCursor] = useState("");
  const [loading, setLoading] = useState(false);
  const [loadingWord, setLoadingWord] = useState(false);
  let currentWord = word[currentIndex];
  const [query, setQuery] = useState('');
  const [activeTab, setActiveTab] = useState("Wordlist");
  const [selectedWord, setSelectedWord] = useState("")

  const tabs = ["Wordlist", "History", "Favorites"]

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

  const loadData = async (endpoint: string, setter: any, post: PostData, load = false) => {
    try {
      if(!!post.cursor){
        setCurrentCursor(post.cursor);
      }
      if(!!load){
        post.cursor = "";
      }
      const response = await apiUtils(endpoint, 'get', post);
      
      if (response && response.results) {
        if(load){
          setter(response.results);
        }else{
          setter((prev: any) => [...prev, ...response.results]);
        }
        return response;
      }
    } catch (error) {
      console.log(error);
      setLoading(false);
    }
  };

  const handleScroll = async (e: any) => {
    const bottom = e.target.scrollHeight === e.target.scrollTop + e.target.clientHeight;
    if (bottom && !loading) {
      let newCursor;
      setLoading(true); // Comece o carregamento
  
      try {
        switch (activeTabIndex) {
          case 0:
            if (!!cursorWords && currentCursor !== cursorWords) {
              newCursor = await loadData('/entries/en', setWords, { limit, search, cursor: cursorWords });
              if (!!newCursor.hasNext) {
                setCursorWords(newCursor.next);
              }
            }
            break;
          case 1:
            if (!!cursorHistory && currentCursor !== cursorHistory) {
              newCursor = await loadData('/user/me/history', setHistory, { limit, search, cursor: cursorHistory });
              if (!!newCursor.hasNext) {
                setCursorHistory(newCursor.next);
              }
            }
            break;
          case 2:
            if (!!cursorFavorites && currentCursor !== cursorFavorites) {
              newCursor = await loadData('/user/me/favorites', setFavorites, { limit, search, cursor: cursorFavorites });
              if (!!newCursor.hasNext) {
                setCursorFavorites(newCursor.next);
              }
            }
            break;
          default:
            break;
        }
      } finally {
        setLoading(false); // Finalize o carregamento
      }
    }
  };

  const selectWord = async (word: string) => {
    if (!!word) {
      setLoadingWord(true);
      try {
        const response = await apiUtils(`/entries/en/${word}`, 'get', {});
        if (response && response.response) {
          setWord(response.response);
          setCurrentIndex(0);
        }
      } catch (error) {
        console.log(error);
      }
      setLoadingWord(false);
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

  const markFavorite = () => {
    const currentWordData = word[currentIndex];
    if (currentWordData) {
      favorite(`/entries/en/${currentWordData.word}/favorite`, 'post', {}, "Marcado");
    }
  };

  const unMarkFavorite = () => {
    const currentWordData = word[currentIndex];
    if (currentWordData) {
      favorite(`/entries/en/${currentWordData.word}/unfavorite`, 'delete', {}, "Desmarcado");
    }
  };

  const loadTab = async (value: number) => {
    let cursorResponse = null;
    if(value === 0){
      cursorResponse = await loadData('/entries/en', setWords, { limit, search, cursor: cursorWords }, true);
      if(!!cursorResponse.hasNext){
        setCursorWords(cursorResponse.next);
      }
    }
    if(value === 1){
      cursorResponse = await loadData('/user/me/history', setHistory, { limit, search, cursor: cursorHistory }, true);
      if(!!cursorResponse.hasNext){
        setCursorHistory(cursorResponse.next);
      }
    }
    if(value === 2){
      cursorResponse = await loadData('/user/me/favorites', setFavorites, { limit, search, cursor: cursorFavorites }, true);
      if(!!cursorResponse.hasNext){
        setCursorFavorites(cursorResponse.next);
      }
    }
    setActiveTabIndex(value)
  };

  useEffect(() => {
    const fetchData = async () => {
      const newCursorWords = await loadData('/entries/en', setWords, { limit, search, cursor: cursorWords }, true);
      if(!!newCursorWords.hasNext){
        setCursorWords(newCursorWords.next);
      }
    };
  
    fetchData();
  }, []);

  const handleSearch = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const params = new URLSearchParams(window.location.search);
    params.set('search', query);
    if(!!query){
      window.location.href = `${window.location.pathname}?${params}`;
    } else{
      window.location.href = `${window.location.pathname}`;
    }
  };

  return (
    <div className="md:h-[90vh] sm:h-[100vh] flex justify-center items-center flex-1 z-0 py-5">
      <div className="md:h-auto sm:h-[100vh] grid lg:grid-cols-2 grid-cols-1 gap-4 bg-white rounded-lg sm:rounded-2xl py-14 px-2">
        <div className="md:columns-6 columns-12 flex flex-col items-center justify-start">
          {currentWord ? (
            <>
              <div className="min-w-72 min-h-32 rounded-xl border-2 border-gray-400 flex flex-col justify-center items-center gap-4">
                <span className="font-bold text-lg">{currentWord.word ?? ""}</span>
                <span className="font-semibold text-lg">{currentWord.phonetic ?? 'N/A'}</span>
              </div>              
              {currentWord.phonetics && currentWord.phonetics.length > 0 && (
                <audio 
                  key={currentWord.phonetics[0]?.audio || currentWord.phonetics[1]?.audio || currentWord.phonetics[2]?.audio || ""}
                  controls 
                  className="mt-5">
                  <source                    
                    src={
                      currentWord.phonetics[0]?.audio ||
                      currentWord.phonetics[1]?.audio ||
                      currentWord.phonetics[2]?.audio ||
                      ""
                    }
                    type="audio/mpeg" />
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
                <Button title="<< Voltar" onClick={handlePrevious} disabled={currentIndex === 0} />
                <Button title="Próximo >>" onClick={handleNext} disabled={currentIndex === word.length - 1} />
              </div>
              <div className="min-w-72 flex flex-row justify-center items-center gap-4">
                <Button title="Favoritar" onClick={markFavorite} />
                <Button title="Desfavoritar" onClick={unMarkFavorite} />
              </div>
            </>
          ) : (
            <span className="font-bold text-lg">{!!loadingWord ? "Carregando..." : "Não há dados disponíveis" }</span>
          )}
        </div>
        <div className="md:columns-6 columns-12 flex flex-col items-center justify-start">
          <div className="max-w-2xl grid-cols-1 gap-4 px-5 mx-20 my-5 p-6 bg-gray-100 rounded-xl shadow-lg">
            <form onSubmit={handleSearch} style={{ display: 'flex', alignItems: 'center' }}>
              <div className="mb-4 relative">
                <button type="submit" 
                  className="text-white w-25 bg-indigo-400 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full px-10 py-2 mr-3 my-3">
                  Pesquisar
                </button>
                <input
                  type="text"
                  placeholder="Digite sua pesquisa..."
                  className="w-80 pl-10 pr-4 py-2 rounded-full border border-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  value={query}
                  onChange={(e) => setQuery(e.target.value)}
                />
              </div>
            </form>
            <div className="flex space-x-1 mb-4">
              {tabs.map((tab, index) => (
                <button
                  key={tab}
                  className={`px-4 py-2 rounded-t-lg font-medium transition-colors duration-200 ${
                    activeTab === tab
                      ? "bg-purple-100 text-purple-800"
                      : "bg-gray-200 text-gray-600 hover:bg-gray-300"
                  }`}
                  onClick={() => {setActiveTab(tab), loadTab(index)}}
                >
                  {tab}
                </button>
              ))}
            </div>
            <div className="bg-purple-100 p-6 rounded-lg shadow-inner">
              <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-2 min-w-20"
              style={{ maxHeight: '200px', overflowY: 'auto' }}
              onScroll={handleScroll}>
                {activeTab === "Wordlist" && words.map((word, index) => (
                  <button
                    key={index}
                    className={`px-4 py-2 rounded-full font-medium transition-all duration-200 ${
                      selectedWord === word
                        ? "bg-purple-500 text-white shadow-md transform scale-105"
                        : "bg-white text-purple-800 hover:bg-purple-200 hover:shadow-md"
                    }`}
                    onClick={() => {setSelectedWord(word), selectWord(word)}}
                  >
                    {word}
                  </button>
                ))}
                {activeTab === "History" && history.map((element: any, index) => (
                  <button
                    key={index}
                    className={`px-4 py-2 rounded-full font-medium transition-all duration-200 ${
                      selectedWord === element.word
                        ? "bg-purple-500 text-white shadow-md transform scale-105"
                        : "bg-white text-purple-800 hover:bg-purple-200 hover:shadow-md"
                    }`}
                    onClick={() => {setSelectedWord(element.word), selectWord(element.word)}}
                  >
                    {element.word}
                  </button>
                ))}
                {activeTab === "Favorites" && favorites.map((element: any, index) => (
                  <button
                    key={index}
                    className={`px-4 py-2 rounded-full font-medium transition-all duration-200 ${
                      selectedWord === element.word
                        ? "bg-purple-500 text-white shadow-md transform scale-105"
                        : "bg-white text-purple-800 hover:bg-purple-200 hover:shadow-md"
                    }`}
                    onClick={() => {setSelectedWord(element.word), selectWord(element.word)}}
                  >
                    {element.word}
                  </button>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;
