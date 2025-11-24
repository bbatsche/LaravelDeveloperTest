import axios from "axios";

export const all = async () => {
    const result = await axios.get('/api/posts');

    return result.data;
};

export const find = async (id) => {
    const result = await axios.get(`/api/posts/${id}`);

    return result.data;
};
