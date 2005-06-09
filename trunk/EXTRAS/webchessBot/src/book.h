#include <ctype.h>

//#include "hash.h" // for hash macros

extern float INI_BOOK_MEMORY_MB;	
extern int INI_BOOK_SIZE;	
extern char INI_BOOK_FILE[40];	

#define CHECK_IN_BOOK_UNTIL_PLY		20 // check for the first X plies if we have a book move

#define BOOK_IO_VERSION				"3.00" // MUST always be 4 chars long

// book entry
typedef struct {
	unsigned __int64 hashkey;
	move Move;
} book_t;

extern book_t	*book;




book_t *book;

float INI_BOOK_MEMORY_MB = 0; 
int INI_BOOK_SIZE = 0; 
char INI_BOOK_FILE[40]; 



void InitBook()
{
	INI_BOOK_SIZE = GetBookSize(INI_BOOK_FILE);
	INI_BOOK_MEMORY_MB = ((float)INI_BOOK_SIZE * sizeof(book_t))/1000000;
	book = (book_t *)malloc(INI_BOOK_SIZE * sizeof(book_t));

	if(book == NULL) {
		printf("Opening book memory allocation failed!!!\n");
		exit(EXIT_FAILURE);
	}
	printf("Openning book memory allocated.\n");
	printf("Book size: %d\tBook memory footprint: %.2f MB\n", INI_BOOK_SIZE, INI_BOOK_MEMORY_MB);
}

void BookCleanup(void)
{
	if(book != NULL) free(book);
	printf("Book memory freed.\n");
}



move SearchBook()
{
	// expects unused elements zeroed out

	// if this gets too slow with a large book, we could always sort by hashkey and 
	// do a binary search, but right now it's not worth the effort.

	int pos_moves = 0;
	int p;

	int i;
	move nm;
	nm.id = 0;

	

	for(i = 0; i < INI_BOOK_SIZE; i++) {
		if(book[i].hashkey == board_key) {
			pos_moves++;
		//	return book[i].Move;
		}
		if(book[i].hashkey == 0) break;
	}

	if(pos_moves == 0) return nm; // no moves in book

	p = rand()%pos_moves;
	printf("Choosing move %i from %i possible moves in book.\n", p+1, pos_moves);
	
	pos_moves = 0;

	for(i = 0; i < INI_BOOK_SIZE; i++) {
		if(book[i].hashkey == board_key) {
			if(p == pos_moves) return book[i].Move;
			pos_moves++;
		}
	}
	

	return nm; 
}