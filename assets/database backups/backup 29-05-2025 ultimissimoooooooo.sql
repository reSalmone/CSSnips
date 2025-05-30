PGDMP      .                }           postgres    17.4    17.4 7    m           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            n           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            o           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            p           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false            q           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4976            �            1255    17418    update_snip_likes()    FUNCTION       CREATE FUNCTION public.update_snip_likes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE snips
    SET challenge_points = (
        SELECT COUNT(*) FROM user_snip_likes WHERE snip_id = NEW.snip_id
    )
    WHERE id = NEW.snip_id;
    RETURN NULL;
END;
$$;
 *   DROP FUNCTION public.update_snip_likes();
       public               postgres    false            �            1259    17419 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    image text,
    type text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    17425    comments    TABLE     
  CREATE TABLE public.comments (
    id integer NOT NULL,
    creator character varying(16),
    post_name character varying(16),
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    child_of integer,
    likes integer DEFAULT 0
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    17432    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218            r           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    17433    drafts    TABLE     ,  CREATE TABLE public.drafts (
    id integer NOT NULL,
    creator text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    type character varying(32),
    description text,
    tags text[],
    file_location character varying(255),
    variation_of text,
    challenge_of text
);
    DROP TABLE public.drafts;
       public         heap r       postgres    false            �            1259    17439    drafts_id_seq    SEQUENCE     �   CREATE SEQUENCE public.drafts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.drafts_id_seq;
       public               postgres    false    220            s           0    0    drafts_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.drafts_id_seq OWNED BY public.drafts.id;
          public               postgres    false    221            �            1259    17440    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variation_of text,
    challenge_of text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    17449    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    222            t           0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    223            �            1259    17450    snips_with_likes    VIEW     �  CREATE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
 #   DROP VIEW public.snips_with_likes;
       public       v       postgres    false            �            1259    17454    user_snip_likes    TABLE     d   CREATE TABLE public.user_snip_likes (
    user_id integer NOT NULL,
    snip_id integer NOT NULL
);
 #   DROP TABLE public.user_snip_likes;
       public         heap r       postgres    false            �            1259    17457    users    TABLE     f  CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[],
    bio text DEFAULT 'This is my bio :)'::text,
    followers text[],
    following text[],
    remember_token text
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    17463    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    226            u           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    227            �            1259    17464    users_with_likes    VIEW     	  CREATE VIEW public.users_with_likes AS
 SELECT u.id,
    u.username,
    u.email,
    u.password,
    u.likedsnippets,
    u.savedsnippets,
    u.bio,
    u.followers,
    u.following,
    u.remember_token,
    COALESCE(sum(s.challenge_likes), (0)::numeric) AS user_challenge_likes
   FROM (public.users u
     LEFT JOIN public.snips_with_likes s ON (((u.username)::text = s.creator)))
  GROUP BY u.id, u.username, u.email, u.password, u.likedsnippets, u.savedsnippets, u.bio, u.followers, u.following, u.remember_token;
 #   DROP VIEW public.users_with_likes;
       public       v       postgres    false    226    226    224    224    226    226    226    226    226    226    226    226            �           2604    17469    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            �           2604    17470 	   drafts id    DEFAULT     f   ALTER TABLE ONLY public.drafts ALTER COLUMN id SET DEFAULT nextval('public.drafts_id_seq'::regclass);
 8   ALTER TABLE public.drafts ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            �           2604    17471    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            �           2604    17472    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    227    226            a          0    17419 
   challenges 
   TABLE DATA           c   COPY public.challenges (name, description, date_start, date_end, winners, image, type) FROM stdin;
    public               postgres    false    217   ^J       b          0    17425    comments 
   TABLE DATA           `   COPY public.comments (id, creator, post_name, content, created_at, child_of, likes) FROM stdin;
    public               postgres    false    218   �K       d          0    17433    drafts 
   TABLE DATA           }   COPY public.drafts (id, creator, created_at, type, description, tags, file_location, variation_of, challenge_of) FROM stdin;
    public               postgres    false    220   �K       f          0    17440    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variation_of, challenge_of) FROM stdin;
    public               postgres    false    222   RL       h          0    17454    user_snip_likes 
   TABLE DATA           ;   COPY public.user_snip_likes (user_id, snip_id) FROM stdin;
    public               postgres    false    225   \P       i          0    17457    users 
   TABLE DATA           �   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio, followers, following, remember_token) FROM stdin;
    public               postgres    false    226   �P       v           0    0    comments_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.comments_id_seq', 1, true);
          public               postgres    false    219            w           0    0    drafts_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.drafts_id_seq', 24, true);
          public               postgres    false    221            x           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 49, true);
          public               postgres    false    223            y           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 7, true);
          public               postgres    false    227            �           2606    17474    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            �           2606    17476    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            �           2606    17478    drafts drafts_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_pkey;
       public                 postgres    false    220            �           2606    17480    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    222            �           2606    17482    snips unique_file_location 
   CONSTRAINT     ^   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT unique_file_location UNIQUE (file_location);
 D   ALTER TABLE ONLY public.snips DROP CONSTRAINT unique_file_location;
       public                 postgres    false    222            �           2606    17484 $   user_snip_likes user_snip_likes_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_pkey PRIMARY KEY (user_id, snip_id);
 N   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_pkey;
       public                 postgres    false    225    225            �           2606    17486    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    226            �           2606    17488    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    226            �           2606    17490    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    226            _           2618    17453    snips_with_likes _RETURN    RULE     �  CREATE OR REPLACE VIEW public.snips_with_likes AS
 SELECT s.id,
    s.creator,
    s.views,
    s.created_at,
    s.description,
    s.element_type,
    s.tags,
    s.file_location,
    s.likes,
    s.saved,
    s.variation_of,
    s.challenge_of,
    count(usl.snip_id) AS challenge_likes
   FROM (public.snips s
     LEFT JOIN public.user_snip_likes usl ON ((s.id = usl.snip_id)))
  GROUP BY s.id;
 �  CREATE OR REPLACE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
       public               postgres    false    222    222    222    222    222    222    222    222    222    222    222    222    225    4794    224            �           2606    17492    comments comments_child_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_child_of_fkey FOREIGN KEY (child_of) REFERENCES public.comments(id);
 I   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_child_of_fkey;
       public               postgres    false    218    218    4790            �           2606    17497    drafts drafts_challange_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_challange_of_fkey FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 I   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_challange_of_fkey;
       public               postgres    false    4788    217    220            �           2606    17543    drafts drafts_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 D   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_creator_fkey;
       public               postgres    false    220    4804    226            �           2606    17507    comments fk_post_name    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_post_name FOREIGN KEY (post_name) REFERENCES public.snips(file_location);
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_post_name;
       public               postgres    false    4796    222    218            �           2606    17548    comments fk_user_name    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_user_name FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_user_name;
       public               postgres    false    226    4804    218            �           2606    17538    snips snips_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    4804    222    226            �           2606    17522 ,   user_snip_likes user_snip_likes_snip_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_snip_id_fkey FOREIGN KEY (snip_id) REFERENCES public.snips(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_snip_id_fkey;
       public               postgres    false    4794    225    222            �           2606    17527 ,   user_snip_likes user_snip_likes_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_user_id_fkey;
       public               postgres    false    225    226    4802            �           2606    17532    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    4788    217    222            a     x����N�0�5|����YJ\b&�%�)�R�����e&����wqO�ƘK<�^�+�zm: Y���*�rI�����!W9|ʡ�iEhI%�	sH�K�U�:�yl�x;�{ ?<x찻s}޳`��sQ�lqc�L�Ȋ�Ϭ�e�J�#+�ﵰ.;�Rl���X��ioW˪Y�-U0Q�R���5v��زh{K�&����u�I�L��i����7,6^Q֥�~���f���Q��f���<�-��)n��50�ĵm��=R�      b   O   x�3�,I-.�y�%��
@��P������������W�id`d�k`�kd�`hbehhed�g`blj���i����� �P      d   c   x��K@0 ���� a�����"#>m	#~qw^� �	`�D ��q^PRn4��"���6�w��|h���</t�9v֝��f���_աR��       f   �  x��Uko�8���
��b���Jm���I���ڕ"U<�lffH4�}�&����@<l�s��\8G�\��1Z"!��s�O)My�- �U��p6#�r�e-�-�k�F/;T���x��B!
G)`]�Bɵ��Ҏ�<"$�Fi�B��8�ԧ^��3$+�˦�$���{�3�p$�D6��~���N��\)Q׆|�3�ء�\�]�<�G���X�Kts�3~ʂ�,�?���?m�g�z�� ɯ x�Ggq���H�7ej�p��>;T��y�֍�N�y<�!��S�)O�$c��E�L+�oymz���j�2�ae�g:���ec֒|�V�,���ϛl���؉����"ٳ��#���a�-x�#�I[��e�
�i�����+�%6�V���"������8��_A��Ҋ�'���;��6�YgO�x��{>G*�ﻤ���i��"�$�+�W
D,PmD!;P[m�G2]{��4x�����3��+_Vꐋ[��5����,h���Fi�<J9�H����v��M�ɶ�����8R���A��Q���wUV�8�c9�9�e��n�^����-E�K���7��:�x�s:+��oa��	Ę��K���{a:�[Q�R۽�� ��1����gP@�RK1���4��E�s>W�2��+�a��M�/���?|��~���+�i5�V�[�7h�n�{��~Z�`.���S(o����rT�J�����Х$�J�c�y��u�N��&{�:k�A�yn�=;1>$�O�(��M+&�%zka[ݛZ�[�\k'�C�ɵ�A�OV�>h���Ў�Mmf�`����:�̣� ?�:j��cU�+y��`����A>C��I%����klF��X�xPk��{W���v�m��ҷ2ܘՠ�)�sE�0������n�6Jf��y���D����q�s�f���Q1hm�����8?��|3��:�tA���F������3��,��� ��lp      h      x�3�42�2��Ʀ\1z\\\ ��      i   C  x���K��@���+zћI �¬Fm��Q:�)���U<����>��$N���TN*wqS�9�.GPq�SHŨF�Ǝ��o~PL;8���q�>��b��c���M��b+5*�Q�ki�u���4	6#B��\�C�I	,	J��M�P�pV�=�?|�B��?�R*a��x0�qto��"��L#���3^�S���gkM�N�f+5d��k5iش����!��tq��ｨ�>E�bi��Z3�I]�:l�ٔ��T�e��!�f����������F?�{��:D��NN
6�()�Ɔ�R[�uo��e�f4V���Ѐb�r}?�O�E(����1<c����yL���6�wsD&�����u�φ�j�p �{�2�a[���������{�|�����2�bȋ�Mφt�U���O��]$f�n�Pz�F�
�^Q��E���Z�>�p���t&՜N!�����H�`�*�Z֝p�H�\-�3��A%��Q�ȫ��v��?6��}�6�E}��y��]�f���oP�⦄m�};'�N���4w�e�/َ��^b���^���]Q�     