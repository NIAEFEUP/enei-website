import type Model from "@/Types/Model";
import type EventDay from "@/Types/EventDay";
import type Speaker from "@/Types/Speaker";

export default interface Event extends Model {
    name: string;
    time_start: string;
    time_end: string;
    topic: string;
    capacity?: number;
    event_day_id: number;
    edition_id: number;
    description: string;
    room: string;
    speakers?: Speaker[];
    event_day?: EventDay;
}
