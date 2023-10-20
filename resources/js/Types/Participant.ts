import type Model from "./Model";
import type SocialMedia from "./SocialMedia";
import type { User } from "./User";

export default interface Participant extends Model {
    user_id: number;
    user?: User;
    social_media_id?: number;
    social_media?: SocialMedia;
    cv_path?: string;
    cv_url?: string;
    quest_qr_code?: string;
    quest_code?: string;
}
