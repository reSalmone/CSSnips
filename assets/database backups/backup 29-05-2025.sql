PGDMP  .    #                }           postgres    17.4    17.4 7               0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            	           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            
           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false                       1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false                       0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4875            �            1255    25436    update_snip_likes()    FUNCTION       CREATE FUNCTION public.update_snip_likes() RETURNS trigger
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
       public               postgres    false            �            1259    25969 
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
       public         heap r       postgres    false            �            1259    25975    comments    TABLE     
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
       public         heap r       postgres    false            �            1259    25981    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218                       0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    25982    drafts    TABLE     ,  CREATE TABLE public.drafts (
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
       public         heap r       postgres    false            �            1259    25988    drafts_id_seq    SEQUENCE     �   CREATE SEQUENCE public.drafts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.drafts_id_seq;
       public               postgres    false    220                       0    0    drafts_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.drafts_id_seq OWNED BY public.drafts.id;
          public               postgres    false    221            �            1259    25989    snips    TABLE     �  CREATE TABLE public.snips (
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
       public         heap r       postgres    false            �            1259    25998    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    222                       0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    223            �            1259    25999    snips_with_likes    VIEW     �  CREATE VIEW public.snips_with_likes AS
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
       public       v       postgres    false            �            1259    26003    user_snip_likes    TABLE     d   CREATE TABLE public.user_snip_likes (
    user_id integer NOT NULL,
    snip_id integer NOT NULL
);
 #   DROP TABLE public.user_snip_likes;
       public         heap r       postgres    false            �            1259    26006    users    TABLE     f  CREATE TABLE public.users (
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
       public         heap r       postgres    false            �            1259    26012    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    226                       0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    227            �            1259    26013    users_with_likes    VIEW     	  CREATE VIEW public.users_with_likes AS
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
       public       v       postgres    false    226    226    226    226    226    226    226    226    226    226    224    224            B           2604    26018    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            E           2604    26019 	   drafts id    DEFAULT     f   ALTER TABLE ONLY public.drafts ALTER COLUMN id SET DEFAULT nextval('public.drafts_id_seq'::regclass);
 8   ALTER TABLE public.drafts ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            G           2604    26020    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            L           2604    26021    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    227    226            �          0    25969 
   challenges 
   TABLE DATA           c   COPY public.challenges (name, description, date_start, date_end, winners, image, type) FROM stdin;
    public               postgres    false    217   :J       �          0    25975    comments 
   TABLE DATA           `   COPY public.comments (id, creator, post_name, content, created_at, child_of, likes) FROM stdin;
    public               postgres    false    218   \K       �          0    25982    drafts 
   TABLE DATA           }   COPY public.drafts (id, creator, created_at, type, description, tags, file_location, variation_of, challenge_of) FROM stdin;
    public               postgres    false    220   �K                 0    25989    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variation_of, challenge_of) FROM stdin;
    public               postgres    false    222   .L                 0    26003    user_snip_likes 
   TABLE DATA           ;   COPY public.user_snip_likes (user_id, snip_id) FROM stdin;
    public               postgres    false    225   ?P                 0    26006    users 
   TABLE DATA           �   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio, followers, following, remember_token) FROM stdin;
    public               postgres    false    226   hP                  0    0    comments_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.comments_id_seq', 1, true);
          public               postgres    false    219                       0    0    drafts_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.drafts_id_seq', 24, true);
          public               postgres    false    221                       0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 49, true);
          public               postgres    false    223                       0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 7, true);
          public               postgres    false    227            O           2606    26023    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            Q           2606    26025    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            S           2606    26027    drafts drafts_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_pkey;
       public                 postgres    false    220            U           2606    26029    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    222            W           2606    32995    snips unique_file_location 
   CONSTRAINT     ^   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT unique_file_location UNIQUE (file_location);
 D   ALTER TABLE ONLY public.snips DROP CONSTRAINT unique_file_location;
       public                 postgres    false    222            Y           2606    26031 $   user_snip_likes user_snip_likes_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_pkey PRIMARY KEY (user_id, snip_id);
 N   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_pkey;
       public                 postgres    false    225    225            [           2606    26033    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    226            ]           2606    26035    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    226            _           2606    26037    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    226            �           2618    26002    snips_with_likes _RETURN    RULE     �  CREATE OR REPLACE VIEW public.snips_with_likes AS
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
       public               postgres    false    222    222    222    222    222    222    222    222    225    4693    222    222    222    222    224            `           2606    32971    comments comments_child_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_child_of_fkey FOREIGN KEY (child_of) REFERENCES public.comments(id);
 I   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_child_of_fkey;
       public               postgres    false    4689    218    218            c           2606    26049    drafts drafts_challange_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_challange_of_fkey FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 I   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_challange_of_fkey;
       public               postgres    false    217    220    4687            d           2606    26054    drafts drafts_creator_fkey    FK CONSTRAINT        ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username);
 D   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_creator_fkey;
       public               postgres    false    226    220    4703            a           2606    32996    comments fk_post_name    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_post_name FOREIGN KEY (post_name) REFERENCES public.snips(file_location);
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_post_name;
       public               postgres    false    4695    222    218            b           2606    33001    comments fk_user_name    FK CONSTRAINT     z   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_user_name FOREIGN KEY (creator) REFERENCES public.users(username);
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_user_name;
       public               postgres    false    218    4703    226            e           2606    26059    snips snips_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    4703    222    226            g           2606    26064 ,   user_snip_likes user_snip_likes_snip_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_snip_id_fkey FOREIGN KEY (snip_id) REFERENCES public.snips(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_snip_id_fkey;
       public               postgres    false    222    4693    225            h           2606    26069 ,   user_snip_likes user_snip_likes_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_user_id_fkey;
       public               postgres    false    225    226    4701            f           2606    26074    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    4687    222    217            �     x����N�0�5|����YJ\b&�%�)�R�����e&����wqO�ƘK<�^�+�zm: Y���*�rI�����!W9|ʡ�iEhI%�	sH�K�U�:�yl�x;�{ ?<x찻s}޳`��sQ�lqc�L�Ȋ�Ϭ�e�J�#+�ﵰ.;�Rl���X��ioW˪Y�-U0Q�R���5v��زh{K�&����u�I�L��i����7,6^Q֥�~���f���Q��f���<�-��)n��50�ĵm��=R�      �   O   x�3�,I-.�y�%��
@��P������������W�id`d�k`�kd�`hbehhed�g`blj���i����� �P      �   c   x��K@0 ���� a�����"#>m	#~qw^� �	`�D ��q^PRn4��"���6�w��|h���</t�9v֝��f���_աR��            x���mo�8�_;��}�r؆��J��]u��nu�Vw�J+���IB�|�y�t�*($<����"+[������k׿�S{Q�N��<D�����4�ęl�F�V��V��2�Ig��h:��C���YK�Y��Jr3���:ߡ�y����\���
ǌG�,����b�c�w�<����-d�ۍ�i�SS���e?kI,Sa�K�%�߄^�|1C�N��BV�,�hZrkL�CO�n��u�҈�a�}>�1�Ǯ;@���E�)��E�H�/U.�@��l��Mi�'2~��q�f�^v9��i���BXܫ�,�׌s�ȅ(+��Y.s�xLi�Cgz�g�P-��걖�+��� ;��U�=nl�@&E��5�̔\�/l_��T��T�i�A�1�R�A�;��=n�uZ�d;��T:�S��?'K����7�=7x#D�y��}����y���;�^�1�S�[��n�7�*����?�t=L#��̉���{2`+�wG�>�Ods��Z��b�Ʈ������W��0��K�`�whn=<yuu�������)�w��nr/�J@���3�~�R�T�Ӵ�9�P^f͙�#�Q!y2��F�\�eY��1����:;y���a��!Ĵ�ٌO�gF���	B��x�h1g���խ)%����8P �|)���JѦ��� {Y�4:�f��� ���r�3$�ʪn$.n!vkpb��'"�/)E'wJ �Y<J�F��R.ۦ�V���=wj������������8���d�|v�VE��EV/��p�/�ҘEN�����P�Y�j�>��s�n_��7��_�!���!@�j��b4c�-�*��1�����Z˦ݛ%`���k�S�O���,;���4!�Ô�߷5ئlvh�tf6��.�܋�B�c7p8�[�����2��W~J�f�!;T��z.֕m^c��,f�8`���b�NkC�X��T��_��wSv���t:#5��ȟ����z�4=���g6��wnlp            x�3�42�2��Ʀ\1z\\\ ��         G  x���]o�@���WxћM ��^�Z�A,
Kӛ���A@��^�v7v�����\���g^�*��g�J�a�.�~)@	�┺㺻!{ײ�|ù�d?R����'vf�������ʰ�YnƄ�g���`�:X��W�.DU�\i�w��F�h��7�rT�$9�)�pt��V�åe"cXf<�1{�yΌ.�5M:C�����*Ϭ�dQ��:���x�}��^�pt�E��8���(��Ou`��6���N��eTU:1Y^�	o��|P�A�\�������c<}$`���mX/����]�m�m-9��E&ە@�5JI)�`
�&�L ǲ����l}f+�mB�$kL�G�Ž�uCy����h(;{�6�Q[i���1�e���[�b/��#�d3���g��@:�j���{M�)+�6Y$?lcv�B����{��/�x�0�|���d��lB&��N%��ԃ�X�`��Z1�Hre:� Xs��A5��Q�د��"|D� J�(:/j�� �0����}�{pР��M�<�%,NA ��(��vn�{��!/<]6���B�z�7�T     