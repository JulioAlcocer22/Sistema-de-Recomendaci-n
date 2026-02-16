import Content_Curator
import Content_Matcher

class Recommender:
    @staticmethod
    def recommend_based_on_content(actual_ted_talk, NUMBER_OF_RECOMMENDATIONS=5):
        """
        Recommend TED Talks based on content similarity.
        """
        content_curator = Content_Curator.Content_Curator()
        content_matcher = Content_Matcher.Content_Matcher()
        
        catalogue_ted_talks = content_curator.get_all_ted_talks()
        actual_ted_talk_info = content_curator.get_ted_talk_by_title(actual_ted_talk)
        
        recommendations = content_matcher.match_by_tags(actual_ted_talk_info, catalogue_ted_talks)
        recommendations = sorted(recommendations, key=lambda x: x['views'], reverse=True)
        
        return recommendations[:NUMBER_OF_RECOMMENDATIONS]
    
    @staticmethod
    def recommend_based_on_user_interactions(USER_ID=1, NUMBER_OF_RECOMMENDATIONS=5):
        """
        Recommend TED Talks based on user interactions.
        """
        content_curator = Content_Curator.Content_Curator()
        content_matcher = Content_Matcher.Content_Matcher()
        
        interactions_df = content_curator.get_all_interactions()
        
        similarity_matrix = content_matcher.calculate_similarity_matrix(interactions_df)
        
        user_similarity = similarity_matrix[USER_ID]
        similar_users = user_similarity.argsort()[::-1][1:6]
        
        user_interactions = interactions_df[interactions_df['user_id'] == USER_ID]
        watched_talks = set(user_interactions['talk_id'])
        
        recommended_talks = set()
        for similar_user_id in similar_users:
            similar_user_interactions = interactions_df[interactions_df['user_id'] == similar_user_id]
            similar_user_talks = set(similar_user_interactions['talk_id'])
            # Filtrar las charlas nuevas que no ha visto el usuario objetivo
            new_recommendations = similar_user_talks - watched_talks
            recommended_talks.update(new_recommendations)
                        
        # Convert talk IDs to detailed metadata
        final_recommendations = []
        for talk_id in list(recommended_talks)[:NUMBER_OF_RECOMMENDATIONS]:
            talk_info = content_curator.get_ted_talk_by_id(talk_id)
            if talk_info:
                final_recommendations.append(talk_info)

        return final_recommendations
    
    @staticmethod
    def recommend_based_on_user_interactions_and_content(user_recommended_talks, NUMBER_OF_RECOMMENDATIONS=5):
        """
        Recommend TED Talks based on both user interactions and content similarity.
        Returns a dictionary mapping each watched talk to its recommended talks.
        """
        content_curator = Content_Curator.Content_Curator()
        content_matcher = Content_Matcher.Content_Matcher()
        
        recommendations = {}
        
        print(f"User recommended talks: {user_recommended_talks}")
        
        return recommendations
    
    @staticmethod
    def recommend_random_ted_talk():
        """
        Recommend a random TED Talk.
        """
        content_curator = Content_Curator.Content_Curator()
        return content_curator.get_random_ted_talk()