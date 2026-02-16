import pandas as pd
import csv
import random

class Content_Curator:
    @staticmethod
    def get_all_ted_talks(DATASET_PATH = "../Data/Talks_Dataset.csv"):
        """
        Get a list of all TED Talks from the dataset.
        """
        ted_talks = {}

        with open(DATASET_PATH, newline='', encoding='utf-8') as csvfile:
            reader = csv.DictReader(csvfile)

            for row in reader:
                title = row['title']
                ted_talks[title] = {
                    'main_speaker': row['main_speaker'],
                    'description': row['description'],
                    'tags': row['tags'],
                    'url': row['url'],
                    'views': int(row['views'])  # Convertimos views a entero
                }

        return ted_talks
    
    def get_ted_talk_by_id(self, talk_id, DATASET_PATH = "../Data/Talks_Dataset.csv"):
        """
        Retrieves TED Talk metadata by row index (talk_id).
        """
        with open(DATASET_PATH, newline='', encoding='utf-8') as csvfile:
            reader = csv.DictReader(csvfile)
            for idx, row in enumerate(reader):
                if idx == talk_id:
                    return {
                        'title': row.get('title', '').strip(),
                        'main_speaker': row.get('main_speaker', '').strip(),
                        'description': row.get('description', '').strip(),
                        'url': row.get('url', '').strip(),
                        'views': int(row.get('views', 0)),
                        'tags': row.get('tags', '').strip() if 'tags' in row else ''
                    }
        return None

    @staticmethod
    def get_ted_talk_by_title(title):
        """
        Get a TED Talk by its title.
        """
        try:
            ted_talks_information = Content_Curator.get_all_ted_talks()
            ted_talk = ted_talks_information[title]
            #print(f"TED Talk found: {title}")
            return {'title': title, **ted_talk}
        except Exception as e:
            #print(f"Error al obtener la TED Talk: {e}")
            return None
        
    @staticmethod
    def get_all_interactions(INTERACTIONS_DATASET = "../Data/Interactions.csv"):
        """
        Get a list of all interactions from the dataset.
        """
        df = pd.read_csv(INTERACTIONS_DATASET, sep=",")
        df = df[df['percentage_watched'] > 10]
        
        return df
    
    @staticmethod
    def get_random_ted_talk():
        """
        Get a random selection of TED Talks.
        """
        ted_talks = Content_Curator.get_all_ted_talks()
        random_talk = random.choice(list(ted_talks.items()))
        return {'title': random_talk[0], **random_talk[1]}  