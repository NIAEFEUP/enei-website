import type Model from "@/Types/Model";
import type Stand from "@/Types/Stand";
import type Event from "@/Types/Event";
import type Competition from "./Competition";

export default interface EventDay extends Model {
    date: Date;
    theme: string;
    edition_id: number;

    stands?: Stand[];

    events?: Event[];
    talks?: Event[];
    activities?: Event[];
    competitions?: Competition[];
}
