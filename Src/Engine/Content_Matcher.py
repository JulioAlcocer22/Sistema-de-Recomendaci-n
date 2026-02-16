import ast
from sklearn.metrics.pairwise import cosine_similarity
from scipy.sparse import csr_matrix
import pandas as pd

class Content_Matcher:

    @staticmethod
    def match_by_tags(actual_ted_talk, catalogue_ted_talks):
        """
        Match TED Talks by their tags.
        Devuelve una lista de diccionarios donde la clave es el título y el valor es el registro completo.
        """
        recommended_talks = []

        for title, info in catalogue_ted_talks.items():
            if title != actual_ted_talk['title'] and Content_Matcher.is_content_similar(actual_ted_talk, info):
                recommended_talks.append({'title': title, **info})
                
        return recommended_talks
    
    @staticmethod
    def is_content_similar(content1, content2):
        """
        Check if two TED Talks contents are similar by their tags.
        """
        return Content_Matcher.__dice_similarity(content1['tags'], content2['tags']) > 0.4
    
    @staticmethod
    def __dice_similarity(tags1, tags2):
        """
        Calculate the Dice similarity between tags of two TED Talks.
        """
        tags1, tags2 = ast.literal_eval(tags1), ast.literal_eval(tags2)
        tags1_set, tags2_set = set(tags1), set(tags2)
        
        number_common_tags = len(tags1_set & tags2_set)
        number_total_tags = len(tags1_set) + len(tags2_set)
        
        return (2 * number_common_tags) / (number_total_tags)
    
    @staticmethod
    def calculate_similarity_matrix(interactions_df):
        """
        Calculate the similarity matrix from interactions DataFrame.
        """
        utility_matrix = Content_Matcher.__create_utility_matrix(interactions_df)
        sparse_matrix = csr_matrix(utility_matrix.values)
        similarity_matrix = cosine_similarity(sparse_matrix)
        
        return similarity_matrix
    
    @staticmethod
    def __create_utility_matrix(interactions_df):
        """
        Create a utility matrix from interactions DataFrame.
        """
        utility_matrix = interactions_df.pivot_table(index='user_id', columns='talk_id', values='rating', fill_value=0)
        return utility_matrix